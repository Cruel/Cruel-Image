<?php

	class Image extends fActiveRecord {
		protected $file;

		protected function configure() {
			//
		}

		public function genId(){
			// Generate random UUIDs until it is unique
			while(1) {
				$uuid = '';
				while (strlen($uuid) < CI_UUID_LENGTH){
					$uuid .= substr(CI_UUID_CHARS, rand(0,strlen(CI_UUID_CHARS)-1), 1);
				}
				try {
					$tmp = new Image($uuid);
				} catch (fNotFoundException $e) {
					$this->setId($uuid);
					break;
				}
			 }
		}

		public function getIp(){
			return inet_ntop(parent::getIp());
		}

		public function storeFile($file){
			$this->file = $file;
			if (!$this->getId())
				$this->genId();
			$file_path = str_replace(CI_UPLOAD_DIR, '', $file->getPath());
			
			$this->processImage();

			$this->setFileName($file_path);
			$this->setIp(inet_pton($_SERVER['REMOTE_ADDR']));
			$this->setType($file->getType());
			$this->setWidth($file->getWidth());
			$this->setHeight($file->getHeight());
			$this->setTime(time());
			$this->store();
		}

		protected function processImage(){
			$this->orientImage();
			$thumb = $this->file->duplicate(CI_UPLOAD_DIR.'thumb/');
			$thumb->resize(CI_THUMBNAIL_WIDTH, 0);
			$thumb->saveChanges();
		}

		protected function orientImage() {
			$file_path = CI_UPLOAD_DIR . $this->getFileName();
			$exif = @exif_read_data($file_path);
			if ($exif === false)
				return false;
			$orientation = intval(@$exif['Orientation']);
			if (!in_array($orientation, array(3, 6, 8)))
				return false;
			switch ($orientation) {
				case 3: $this->file->rotate(180); break;
				case 6: $this->file->rotate(270); break;
				case 8: $this->file->rotate(90); break;
				default:
					return false;
			}
			$this->file->saveChanges();
			return true;
		}

		protected function orientImage2() {
			$file_path = CI_UPLOAD_DIR . $this->getFileName();
			$exif = @exif_read_data($file_path);
			if ($exif === false)
				return false;
			$orientation = intval(@$exif['Orientation']);
			if (!in_array($orientation, array(3, 6, 8)))
				return false;
			$image = @imagecreatefromjpeg($file_path);
			switch ($orientation) {
				case 3: $image = @imagerotate($image, 180, 0); break;
				case 6: $image = @imagerotate($image, 270, 0); break;
				case 8: $image = @imagerotate($image, 90, 0); break;
				default:
					return false;
			}
			$success = imagejpeg($image, $file_path);
			// Free up memory (imagedestroy does not delete files):
			@imagedestroy($image);
			return $success;
		}

		public function delete( $force_cascade=FALSE ){
			// Delete all related files
			unlink(CI_UPLOAD_DIR . $this->getFileName());
			unlink(CI_UPLOAD_DIR .'thumb/'. $this->getFileName());
			parent::delete($force_cascade);
		}

	}
