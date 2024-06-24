<?php

namespace App\Twig\Components;

use App\Entity\Company;
use App\Form\CompanyEditType;
use App\Service\CompanyFormHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class CompanyEditForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    private CompanyFormHelper $companyFormHelper;
    private TranslatorInterface $translator;

    #[LiveProp()]
    public string $base64Photo = '';

    #[LiveProp]
    public string $photoUploadError = '';

    public function __construct(CompanyFormHelper $companyFormHelper, ValidatorInterface $validator, TranslatorInterface $translator)
    {
        $this->companyFormHelper = $companyFormHelper;
        $this->validator = $validator;
        $this->translator = $translator;
    }

    public function preFillForm(FormInterface $form, Company $company): void
    {
        $this->companyFormHelper->EditFormFields($form, $company);
    }

    /**
     * The initial data used to create the form.
     */
    #[LiveProp]
    public Company $initialFormData;

    protected function instantiateForm(): FormInterface
    {
        // we can extend AbstractController to get the normal shortcuts
        $form = $this->createForm(CompanyEditType::class, $this->initialFormData);

        $this->preFillForm($form, $this->initialFormData);

        return $form;
    }

    #[LiveAction]
    public function updatePicturePreview(Request $request)
    {
        $this->photoUploadError = '';
        $file = $request->files->get('company_edit')['picture'];
        if ($file instanceof UploadedFile) {
            $this->validateSingleFile($file);
            $this->base64Photo = base64_encode(file_get_contents($file->getPathname()));
            $this->dispatchBrowserEvent('picture:changed', ["base64" => $this->base64Photo]);
        }
    }

    private function validateSingleFile(UploadedFile $singleFileUpload): void
    {
        $errors = $this->validator->validate($singleFileUpload, [
            new Image([
                'maxSize' => '2M',
                'mimeTypes' => [
                    'image/png',
                    'image/jpeg',
                ],
            ]),
        ]);

        if (0 === \count($errors)) {
            return;
        }

        $this->photoUploadError = $errors->get(0)->getMessage();
        $this->dispatchBrowserEvent('picture:changed', ["base64" => ""]);

        // causes the component to re-render
        throw new UnprocessableEntityHttpException('Validation failed');
    }

    #[LiveAction]
    public function save(Request $request, EntityManagerInterface $entityManager)
    {
        $this->submitForm();

        if ($this->getForm()->isSubmitted() && $this->getForm()->isValid()) {
            $company = $this->getForm()->getData();

            // Photo
            if (!is_null($this->base64Photo) && !empty($this->base64Photo)) {
                $company->setPhoto($this->base64Photo);
            }

            $this->companyFormHelper->updateCompanyEntity($this->getForm(), $company);

            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash('success', $this->translator->trans('edit.success'));

        }
        return $this->redirectToRoute('app_company', ['id' => $company->getId()]);

    }
}