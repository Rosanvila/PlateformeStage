<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig\Components;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentToolsTrait;

#[AsLiveComponent]
class UploadPostMediaFiles
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    public function __construct(private ValidatorInterface $validator)
    {
    }
    #[LiveProp]
    public string $multipleFileUploadError = '';

    #[LiveProp]
    public array $multipleUploadFilenames = [];

    #[LiveProp]
    public array $uploadedFilesBase64 = [];

    #[LiveAction]
    public function uploadFiles(Request $request): void
    {
        $this->multipleFileUploadError = '';
        $multiple = $request->files->all('multiple');
        foreach ($multiple as $file) {
            if ($file instanceof UploadedFile) {
                $this->validateSingleFile($file);
                [$filename, $size, $base64Content] = $this->processFileUpload($file);
                $this->multipleUploadFilenames[] = ['filename' => $filename, 'size' => $size];
                $this->uploadedFilesBase64[] = $base64Content;
            }
        }

        $this->emit('base64FilesAdded', [
            'base64FilesArray' => $this->uploadedFilesBase64,
        ]);
    }

    private function processFileUpload(UploadedFile $file): array
    {
        $fileContent = base64_encode(file_get_contents($file->getPathname()));

        return [$file->getClientOriginalName(), $file->getSize(), $fileContent];
    }

    private function validateSingleFile(UploadedFile $singleFileUpload): void
    {
        $errors = $this->validator->validate($singleFileUpload, [
            new Assert\Image([
                'maxSize' => '1M',
            ]),
        ]);

        if (0 === \count($errors)) {
            return;
        }

        $this->multipleFileUploadError = $singleFileUpload->getClientOriginalName() . ' : ' . $errors->get(0)->getMessage();

        // causes the component to re-render
        throw new UnprocessableEntityHttpException('Validation failed');
    }
}
