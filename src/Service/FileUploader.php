<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {

	private $directory;

	public function __construct($targetDirectory) {
		$this->directory = $targetDirectory;
	}

	public function upload(UploadedFile $file, $oldfilename = null) {

		// génération du nouveau nom de fichier
		$filename = md5(uniqid()) . '.' . $file->guessExtension();
		// transfert du fichier
		$file->move($this->directory, $filename);

		if ($oldfilename && file_exists($this->directory . '/' . $oldfilename)) {
			unlink($this->directory . '/' . $oldfilename);
		}

		// je renvoie le nom de fichier généré
		return $filename;

	}

}