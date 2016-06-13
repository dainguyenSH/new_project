<?php

//use Aws\S3\S3Client;
namespace App\Helpers;
class ImageService {

	/**
	 * Type of library to use, defaults to GD
	 * @var string
	 */
	protected $library = 'imagick';

	/**
	 * Instance of Imagine package
	 * @var Imagine\Gd\Imagine
	 */
	protected $imagine;

	/**
	 * Always force overwriting of files
	 * @var boolean
	 */
	public $overwrite = false;

	/**
	 * Quality of compression
	 * @var integer
	 */
	public $quality = 85;

	/**
	 * Initialize image service
	 * @return void
	 */
	public function __construct($library = null)
	{
		
	}

	/**
	 * Resize an image
	 * @param  string  $url
	 * @param  integer $width
	 * @param  integer $height
	 * @param  boolean $crop
	 * @return string
	 */
	public function resize($url, $width = 100, $height = null, $crop = false, $quality = null)
	{
		if ($url)
		{
			// URL info
			$info = pathinfo($url);

			// The size
			if ( ! $height) $height = $width;

			// Quality
			$quality = ($quality) ? $quality : $this->quality;

			// Directories and file names
			$fileName       = $info['basename'];
			$sourceDirPath  = public_path() . $info['dirname'];
			$sourceFilePath = $sourceDirPath . '/' . $fileName;
			$targetDirName  = $width . 'x' . $height . ($crop ? '_crop' : '');
			$targetDirPath  = $sourceDirPath . '/' . $targetDirName . '/';
			$targetFilePath = $targetDirPath . $fileName;
			$targetUrl      = asset($info['dirname'] . '/' . $targetDirName . '/' . $fileName);

			// Create directory if missing
			try
			{
				// Create dir if missing
				if ( ! File::isDirectory($targetDirPath) and $targetDirPath) @File::makeDirectory($targetDirPath);

				// Set the size
				$size = new \Imagine\Image\Box($width, $height);

				// Now the mode
				$mode = $crop ? \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND : \Imagine\Image\ImageInterface::THUMBNAIL_INSET;

				if ($this->overwrite or ! File::exists($targetFilePath) or (File::lastModified($targetFilePath) < File::lastModified($sourceFilePath)))
				{
					$this->imagine->open($sourceFilePath)
					              ->thumbnail($size, $mode)
					              ->save($targetFilePath, array('quality' => $quality));
				}
			}
			catch (\Exception $e)
			{
				//Log::error('[IMAGE SERVICE] Failed to resize image "' . $url . '" [' . $e->getMessage() . ']');
			}

			return $targetUrl;
		}
	}

	/**
	 * Helper for creating thumbs
	 * @param  string  $url
	 * @param  integer $width
	 * @param  integer $height
	 * @return string
	 */
	public function thumb($url, $width, $height = null)
	{
		return $this->resize($url, $width, $height, true);
	}

	/**
	 * Upload an image to the public storage
	 * @param  File $file
	 * @return string
	 */
	public function upload($file, $dir = null)
	{
		if ($file)
		{
			// Generate random dir
			if ( ! $dir) $dir = str_random(8);

			// Get file info and try to move
			$destination = public_path() .  '/uploads/' . $dir;
			$filename    = $file->getClientOriginalName();
			//$path        = '/uploads/' . $dir . '/' . $filename;

			$i = 1;
    
		    while (file_exists( public_path() .  '/uploads/' . $dir . '/' . $filename)) {
		        $filename = str_replace('.', "($i).", $filename);
		        $i++;
		    }

			$uploaded    = $file->move($destination, $filename,false);

			if ($uploaded) return '/uploads/' . $dir . '/' . $filename;
		}
	}

	/*public function uploadS3($src, $key){
		return '999';
        $awsConf = Config::get('aws');
        $bucket = $awsConf['S3Bucket'];
        $acl    = 'public';
        $client = S3Client::factory(
                                    [
                                     'key'    => $awsConf['AccessKeyId'],
                                     'secret' => $awsConf['SecretAccessKey']
                                     ]);
        $handle = fopen($src, 'r');
        $result = $client->putObject
            ([
              'Bucket' => $bucket,
              'Key'    => $key,
              'Body'   => Guzzle\Http\EntityBody::factory($handle),
              'ACL'    => 'public-read',
              ]);
        unlink($src);
        return $client->getObjectUrl($bucket, $key);
  }*/

  public static function delete($src)
  {
      $result = unlink(public_path().$src);
      return $result;
  }

}