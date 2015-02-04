<?php

namespace Application\Form\Filter;

use Administration\Provider\ProvidesEntityManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\MimeType;

class ReportIncidentFormFilter implements InputFilterAwareInterface {

    protected $inputFilter;
    const DESCRIPTION_LIMIT = 10;

    use ProvidesEntityManager;

    // Add content to these methods:
    public function setInputFilter (InputFilterInterface $inputFilter) {

        throw new \Exception("Not used");
    }

    public function getInputFilter () {

        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'anonymous',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'category',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'category',
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'message' => 'Category must be chosen from the list',
                            'callback' => function ($value) {
                                    if ($value !== '0') {
                                        return true;
                                    }
                                    return false;
                                },
                        )
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'type',
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'message' => 'Type must be chosen from the list',
                            'callback' => function ($value) {
                                    if ($value !== '0') {
                                        return true;
                                    }
                                    return false;
                                },
                        )
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'address',
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'message' => 'Location must not be empty',
                            'callback' => function ($value) {
                                    if (!empty($value)) {
                                        return true;
                                    }
                                    return false;
                                },
                        )
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'description',
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'message' => 'Description must be longer than ' . self::DESCRIPTION_LIMIT . ' characters',
                            'callback' => function ($value) {
                                    if (strlen($value) > self::DESCRIPTION_LIMIT) {
                                        return true;
                                    }
                                    return false;
                                },
                        )
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'image',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'callback' => function ($value) {
                                    return $this->validateImage($value);
                                },
                            'message' => 'Image file is invalid.',
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'audio',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'callback' => function ($value) {
                                    return $this->validateAudio($value);
                                },
                            'message' => 'Audio file is invalid.',
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * @param $image
     * @return bool
     */
    private function validateImage ($image)
    {
        return $this->validateFileType($image, 'image');
    }

    /**
     * @param $audio
     * @return bool
     */
    private function validateAudio ($audio)
    {
        return $this->validateFileType($audio, 'audio');
    }

    /**
     *
     * Save any file providing $fileDetails array from $_FILES or formatted uploaded file
     * Provide expected type to allow mime type filtering
     *
     * @param $fileDetails
     * @param $expectedType
     *
     * @throws \Exception if invalid expected type
     * @return boolean/id - return false on failure and unique_id on success
     */
    private function validateFileType ($fileDetails, $expectedType)
    {
        $allowedTypes = array('image', 'audio');

        if (!in_array($expectedType, $allowedTypes)) {
            throw new \Exception('Expected type *' . $expectedType . '* is not supported');
        }

        $mimeTypes = $this->getMimeTypes();

        $audioMimeTypes = array();

        foreach ($mimeTypes as $type) {
            if (strpos($type, 'audio') !== false) {
                $audioMimeTypes[] = $type;
            }
        }

        $imageValidator = new IsImage();
        $audioValidator = new MimeType($audioMimeTypes);

        if ($expectedType == 'image' && $imageValidator->isValid($fileDetails['tmp_name'])) {
            $outputName = uniqid('image', true);
        } else if ($expectedType == 'audio' && $audioValidator->isValid($fileDetails['name'])) {
            $outputName = uniqid('audio', true);
        } else {
            return false;
        }

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $outputMimeType = finfo_file($fileInfo, $fileDetails['tmp_name']);
        finfo_close($fileInfo);

        $globalConfig = $this->serviceLocator->get('config');

        $targetFile = $globalConfig['fileDir'] . basename($outputName);

        if (move_uploaded_file($fileDetails['tmp_name'], $targetFile)) {

            $file = new \Administration\Entity\File();

            $file->setName($outputName);
            $file->setOriginalName($fileDetails['name']);
            $file->setType($expectedType);
            $file->setMimeType($outputMimeType);
            $file->setSize($fileDetails['size']);

            $this->getEntityManager()->persist($file);
            $this->getEntityManager()->flush();

        } else {
            return false;
        }

        return $outputName;
    }

    private function getMimeTypes ()
    {
        return array(
            "323" => "text/h323",
            "acx" => "application/internet-property-stream",
            "ai" => "application/postscript",
            "aif" => "audio/x-aiff",
            "aifc" => "audio/x-aiff",
            "aiff" => "audio/x-aiff",
            "asf" => "video/x-ms-asf",
            "asr" => "video/x-ms-asf",
            "asx" => "video/x-ms-asf",
            "au" => "audio/basic",
            "avi" => "video/x-msvideo",
            "axs" => "application/olescript",
            "bas" => "text/plain",
            "bcpio" => "application/x-bcpio",
            "bin" => "application/octet-stream",
            "bmp" => "image/bmp",
            "c" => "text/plain",
            "cat" => "application/vnd.ms-pkiseccat",
            "cdf" => "application/x-cdf",
            "cer" => "application/x-x509-ca-cert",
            "class" => "application/octet-stream",
            "clp" => "application/x-msclip",
            "cmx" => "image/x-cmx",
            "cod" => "image/cis-cod",
            "cpio" => "application/x-cpio",
            "crd" => "application/x-mscardfile",
            "crl" => "application/pkix-crl",
            "crt" => "application/x-x509-ca-cert",
            "csh" => "application/x-csh",
            "css" => "text/css",
            "dcr" => "application/x-director",
            "der" => "application/x-x509-ca-cert",
            "dir" => "application/x-director",
            "dll" => "application/x-msdownload",
            "dms" => "application/octet-stream",
            "doc" => "application/msword",
            "dot" => "application/msword",
            "dvi" => "application/x-dvi",
            "dxr" => "application/x-director",
            "eps" => "application/postscript",
            "etx" => "text/x-setext",
            "evy" => "application/envoy",
            "exe" => "application/octet-stream",
            "fif" => "application/fractals",
            "flr" => "x-world/x-vrml",
            "gif" => "image/gif",
            "gtar" => "application/x-gtar",
            "gz" => "application/x-gzip",
            "h" => "text/plain",
            "hdf" => "application/x-hdf",
            "hlp" => "application/winhlp",
            "hqx" => "application/mac-binhex40",
            "hta" => "application/hta",
            "htc" => "text/x-component",
            "htm" => "text/html",
            "html" => "text/html",
            "htt" => "text/webviewhtml",
            "ico" => "image/x-icon",
            "ief" => "image/ief",
            "iii" => "application/x-iphone",
            "ins" => "application/x-internet-signup",
            "isp" => "application/x-internet-signup",
            "jfif" => "image/pipeg",
            "jpe" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "jpg" => "image/jpeg",
            "js" => "application/x-javascript",
            "latex" => "application/x-latex",
            "lha" => "application/octet-stream",
            "lsf" => "video/x-la-asf",
            "lsx" => "video/x-la-asf",
            "lzh" => "application/octet-stream",
            "m13" => "application/x-msmediaview",
            "m14" => "application/x-msmediaview",
            "m3u" => "audio/x-mpegurl",
            "man" => "application/x-troff-man",
            "mdb" => "application/x-msaccess",
            "me" => "application/x-troff-me",
            "mht" => "message/rfc822",
            "mhtml" => "message/rfc822",
            "mid" => "audio/mid",
            "mny" => "application/x-msmoney",
            "mov" => "video/quicktime",
            "movie" => "video/x-sgi-movie",
            "mp2" => "video/mpeg",
            "mp3" => "audio/mpeg",
            "mpa" => "video/mpeg",
            "mpe" => "video/mpeg",
            "mpeg" => "video/mpeg",
            "mpg" => "video/mpeg",
            "mpp" => "application/vnd.ms-project",
            "mpv2" => "video/mpeg",
            "ms" => "application/x-troff-ms",
            "mvb" => "application/x-msmediaview",
            "nws" => "message/rfc822",
            "oda" => "application/oda",
            "p10" => "application/pkcs10",
            "p12" => "application/x-pkcs12",
            "p7b" => "application/x-pkcs7-certificates",
            "p7c" => "application/x-pkcs7-mime",
            "p7m" => "application/x-pkcs7-mime",
            "p7r" => "application/x-pkcs7-certreqresp",
            "p7s" => "application/x-pkcs7-signature",
            "pbm" => "image/x-portable-bitmap",
            "pdf" => "application/pdf",
            "pfx" => "application/x-pkcs12",
            "pgm" => "image/x-portable-graymap",
            "pko" => "application/ynd.ms-pkipko",
            "pma" => "application/x-perfmon",
            "pmc" => "application/x-perfmon",
            "pml" => "application/x-perfmon",
            "pmr" => "application/x-perfmon",
            "pmw" => "application/x-perfmon",
            "pnm" => "image/x-portable-anymap",
            "pot" => "application/vnd.ms-powerpoint",
            "ppm" => "image/x-portable-pixmap",
            "pps" => "application/vnd.ms-powerpoint",
            "ppt" => "application/vnd.ms-powerpoint",
            "prf" => "application/pics-rules",
            "ps" => "application/postscript",
            "pub" => "application/x-mspublisher",
            "qt" => "video/quicktime",
            "ra" => "audio/x-pn-realaudio",
            "ram" => "audio/x-pn-realaudio",
            "ras" => "image/x-cmu-raster",
            "rgb" => "image/x-rgb",
            "rmi" => "audio/mid",
            "roff" => "application/x-troff",
            "rtf" => "application/rtf",
            "rtx" => "text/richtext",
            "scd" => "application/x-msschedule",
            "sct" => "text/scriptlet",
            "setpay" => "application/set-payment-initiation",
            "setreg" => "application/set-registration-initiation",
            "sh" => "application/x-sh",
            "shar" => "application/x-shar",
            "sit" => "application/x-stuffit",
            "snd" => "audio/basic",
            "spc" => "application/x-pkcs7-certificates",
            "spl" => "application/futuresplash",
            "src" => "application/x-wais-source",
            "sst" => "application/vnd.ms-pkicertstore",
            "stl" => "application/vnd.ms-pkistl",
            "stm" => "text/html",
            "svg" => "image/svg+xml",
            "sv4cpio" => "application/x-sv4cpio",
            "sv4crc" => "application/x-sv4crc",
            "t" => "application/x-troff",
            "tar" => "application/x-tar",
            "tcl" => "application/x-tcl",
            "tex" => "application/x-tex",
            "texi" => "application/x-texinfo",
            "texinfo" => "application/x-texinfo",
            "tgz" => "application/x-compressed",
            "tif" => "image/tiff",
            "tiff" => "image/tiff",
            "tr" => "application/x-troff",
            "trm" => "application/x-msterminal",
            "tsv" => "text/tab-separated-values",
            "txt" => "text/plain",
            "uls" => "text/iuls",
            "ustar" => "application/x-ustar",
            "vcf" => "text/x-vcard",
            "vrml" => "x-world/x-vrml",
            "wav" => "audio/x-wav",
            "wcm" => "application/vnd.ms-works",
            "wdb" => "application/vnd.ms-works",
            "wks" => "application/vnd.ms-works",
            "wmf" => "application/x-msmetafile",
            "wps" => "application/vnd.ms-works",
            "wri" => "application/x-mswrite",
            "wrl" => "x-world/x-vrml",
            "wrz" => "x-world/x-vrml",
            "xaf" => "x-world/x-vrml",
            "xbm" => "image/x-xbitmap",
            "xla" => "application/vnd.ms-excel",
            "xlc" => "application/vnd.ms-excel",
            "xlm" => "application/vnd.ms-excel",
            "xls" => "application/vnd.ms-excel",
            "xlt" => "application/vnd.ms-excel",
            "xlw" => "application/vnd.ms-excel",
            "xof" => "x-world/x-vrml",
            "xpm" => "image/x-xpixmap",
            "xwd" => "image/x-xwindowdump",
            "z" => "application/x-compress",
            "zip" => "application/zip"
        );
    }

}
