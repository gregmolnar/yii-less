<?php
/**
 * LessCompilationBehavior class file.
 * @author Greg Molnar
 * @copyright Copyright &copy; Greg Molnar
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

require_once dirname(__FILE__).'/../vendors/lessphp/lessc.inc.php';
class LessCompiler extends CApplicationComponent
{
	/**
	 * @property string the base path.
	 */
	public $basePath;
	/**
	 * @property array the paths for the files to parse.
	 */
	public $paths = array();
	/**
	 * @property \Less\Parser the less parser.
	 */
	protected $_parser;

	/**
	 * Initializes the component.
	 * @throws CException if the base path does not exist
	 */
	public function init()
	{
		if ($this->basePath === null)
			$this->basePath = Yii::getPathOfAlias('webroot');

		if (!file_exists($this->basePath))
			throw new CException(__CLASS__.': '.Yii::t('less','Failed to initialize compiler. Base path does not exist!'));

		$this->_parser = new lessc;;
	}

	/**
	 * Compiles the less files.
	 * @throws CException if the source path does not exist
	 */
	public function compile($path)
	{
		if(array_key_exists($path, $this->paths)){
			$less = $this->paths[$path];
			$cache_path = Yii::app()->assetManager->basePath.'/'.md5($path).'.css';
			if(isset($less['precompile']) and $less['precompile'] and file_exists($cache_path)){
				$css = file_get_contents($cache_path);
			}else{
				$css = '';
				foreach($less['paths'] as $fromPath){
					$fromPath = $this->basePath.'/'.$fromPath;				
					if (file_exists($fromPath)){
						try {
						  $css .= $this->_parser->compileFile($fromPath);
						} catch (exception $e) {
						  throw new CException(__CLASS__.': '.Yii::t('less','Failed to compile less file with message: `{message}`.', array('{message}'=>$e->getMessage())));
						}					
					}else{
						throw new CException(__CLASS__.': '.Yii::t('less','Failed to compile less file. Source path('.$fromPath.') does not exist!'));				
					}					
				}
				if(isset($less['precompile']) and $less['precompile']){
					file_put_contents($cache_path, $css);
				}		
			}
			header('Content-Type: text/css; charset=utf-8');
			echo $css;
			die();
		}
	}

	/**
	 * Compiles the less code to css.
	 * @param string $filename the file path to the less file
	 * @return string the css
	 */
	public function compile_($filename)
	{
		try {
		  $css = $this->_parser->compile($filename);
		} catch (exception $e) {
		  throw new CException(__CLASS__.': '.Yii::t('less','Failed to compile less file with message: `{message}`.',
					array('{message}'=>$e->getMessage())));
		}
		return $css;
	}
}
