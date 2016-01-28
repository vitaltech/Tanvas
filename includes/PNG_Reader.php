<?php


class PNG_Reader
{
    private $_chunks;
    private $_fp;

    function __construct($file) {
        if (!file_exists($file)) {
            throw new Exception('File does not exist');
        }

        $this->_chunks = array ();

        // Open the file
        $this->_fp = fopen($file, 'r');

        if (!$this->_fp)
            throw new Exception('Unable to open file');

        // Read the magic bytes and verify
        $header = fread($this->_fp, 8);

        if ($header != "\x89PNG\x0d\x0a\x1a\x0a")
            throw new Exception('Is not a valid PNG image');

        // Loop through the chunks. Byte 0-3 is length, Byte 4-7 is type
        $chunkHeader = fread($this->_fp, 8);

        while ($chunkHeader) {
            // Extract length and type from binary data
            $chunk = @unpack('Nsize/a4type', $chunkHeader);

            // Store position into internal array
            if (isset($this->_chunks[$chunk['type']]) and $this->_chunks[$chunk['type']] === null)
                $this->_chunks[$chunk['type']] = array ();
            $this->_chunks[$chunk['type']][] = array (
                'offset' => ftell($this->_fp),
                'size' => $chunk['size']
            );

            // Skip to next chunk (over body and CRC)
            fseek($this->_fp, $chunk['size'] + 4, SEEK_CUR);

            // Read next chunk header
            $chunkHeader = fread($this->_fp, 8);
        }
    }

    function __destruct() { fclose($this->_fp); }

    // Returns all chunks of said type
    public function get_chunks($type) {
        if (isset($this->_chunks[$type]) and $this->_chunks[$type] === null)
            return null;

        $chunks = array ();

        if(isset($this->_chunks[$type])){
            foreach ($this->_chunks[$type] as $chunk) {
                if ($chunk['size'] > 0) {
                    fseek($this->_fp, $chunk['offset'], SEEK_SET);
                    $chunks[] = fread($this->_fp, $chunk['size']);
                } else {
                    $chunks[] = '';
                }
            }
        }

        return $chunks;
    }
}

// add_filter(
//  'wp_read_image_metadata_types',
//  function( $array ){
//      If(WP_DEBUG) error_log("debug_image_metadata callback | array: ".serialize($array) );
//      if(defined(IMAGETYPE_PNG)) define(IMAGETYPE_PNG, 3);
//      array_push($array, IMAGETYPE_PNG);
//      if(defined(IMAGETYPE_JP2)) define(IMAGETYPE_JP2, 10);
//      array_push($array, IMAGETYPE_JP2);
//      return $array;
//  }
// );

// return apply_filters( 'wp_read_image_metadata', $meta, $file, $sourceImageType );
add_filter( 
    'wp_read_image_metadata', 
    function ($meta, $file='', $sourceImageType=''){
        If(WP_DEBUG) error_log("debug_image_metadata callback | meta: ".serialize($meta)." file: ".serialize($file)." imgtype: ".serialize($sourceImageType));
        
        if(!preg_match('/\.png/', strtolower($file))){
            return $meta;
        }

        $png = new PNG_Reader($file);
        $rawTextData = $png->get_chunks('tEXt');
        $metadata = array();

        foreach($rawTextData as $data) {
           $sections = explode("\0", $data);

           if($sections > 1) {
               $key = array_shift($sections);
               $metadata[$key] = implode("\0", $sections);
           } else {
               $metadata[] = $data;
           }
        }

        // if(WP_DEBUG) error_log("\nMETADATA: ".serialize($metadata));

        if(isset($metadata['title'])){
            $meta['title'] = $metadata['title'];
        }

        if(isset($metadata['description'])){
            $meta['caption'] = $metadata['description'];
        }

        return $meta;

    }, 
    0, 
    3
);

?>