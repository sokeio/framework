<?php

namespace Sokeio\Marketplate;


class ItemUploader extends ItemInstaller
{
    private $extractPath;
    public  function __construct(
        protected $data,
        protected $type,
        private $file
    ) {
        parent::__construct($data, $type);
    }
    // download the zip file stream
    public function uploadZip(): mixed
    {
        return false;
    }
    public function saveAs($path)
    {
        $body = $this->uploadZip();
        if (!$body) {
            return false;
        }
        file_put_contents($path, $body);
        // save the file as
        return true;
    }
    public function processNew()
    {
        $this->extractPath = config('sokeio.platform.updater.temp') . $this->getFolderId() . '-' . $this->getItemInfo()->getVersion() . '-' . time();
        $filename = time() . '.zip';
        $pathFile = config('sokeio.platform.updater.temp') . $filename;
        $this->extractZip($pathFile, $this->extractPath);
        $this->itemNewPath = $this->getItemInfo()->getManager()->getPathWithFileTypeInfo($this->extractPath);
    }
}
