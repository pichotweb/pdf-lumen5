<?php namespace Pichotweb\Pdf;

use Illuminate\Http\Response;

class Pdf {
	protected $dompdf;
	protected $html;
	protected $size;
	protected $orientation;

	public function __construct(){
		$_conf = array('DOMPDF_TEMP_DIR', 'DOMPDF_UNICODE_ENABLED', 'DOMPDF_PDF_BACKEND', 'DOMPDF_DEFAULT_MEDIA_TYPE', 'DOMPDF_DEFAULT_PAPER_SIZE', 'DOMPDF_DEFAULT_FONT', 'DOMPDF_DPI', 'DOMPDF_ENABLE_PHP', 'DOMPDF_ENABLE_REMOTE', 'DOMPDF_ENABLE_CSS_FLOAT', 'DOMPDF_ENABLE_JAVASCRIPT', 'DEBUGPNG', 'DEBUGKEEPTEMP', 'DEBUGCSS', 'DEBUG_LAYOUT', 'DEBUG_LAYOUT_LINES', 'DEBUG_LAYOUT_BLOCKS', 'DEBUG_LAYOUT_INLINE', 'DOMPDF_FONT_HEIGHT_RATIO', 'DEBUG_LAYOUT_PADDINGBOX', 'DOMPDF_ENABLE_HTML5PARSER', 'DOMPDF_ENABLE_FONTSUBSETTING', 'DOMPDF_ADMIN_USERNAME', 'DOMPDF_ADMIN_PASSWORD', 'DOMPDF_FONT_DIR');

		foreach($_conf as $conf) {
			if(env($conf) && !defined($conf)) {
				define($conf, env($conf));
			}
		}

		//TODO - remove project download and versionate it!
		require_once 'dompdf/autoload.inc.php';

		$this->dompdf = new \Dompdf\Dompdf();
	}

	public function load($html, $size = 'A4', $orientation = 'portrait'){
		$this->html = $html;
		$this->size = $size;
		$this->orientation = $orientation;

		$this->dompdf->load_html($this->html);
		$this->setPaper($this->size, $this->orientation);

		return $this;
	}

	private function setPaper($size, $orientation){
		return $this->dompdf->set_paper($size, $orientation);
	}

	private function render(){
		return $this->dompdf->render();
	}

	public function show($filename = 'dompdf_out', $options = array('compress' => 1, 'Attachment' => 0)){
		$this->render();
		$this->clear();
		// return $this->dompdf->stream($filename.'.pdf', $options);

		return response($this->dompdf->stream($filename.'.pdf', $options))
							->header('Content-Type', 'application/pdf');
	}

	public function download($filename = 'dompdf_out', $options = array('compress' => 1, 'Attachment' => 1)){
		$this->render();
		$this->clear();
		return response($this->dompdf->stream($filename.'.pdf', $options))
							->header('Content-Type', 'application/pdf');
	}

	public function output($options = array('compress' => 1)){
		$this->render();
		return $this->dompdf->output($options);
	}

	public function clear(){
		\Dompdf\Image\Cache::clear();
		return true;
	}
}
