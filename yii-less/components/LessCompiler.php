<?php
/**
 * LessCompilationBehavior class file.
 * @author Greg Molnar
 * @copyright Copyright &copy; Greg Molnar
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

Yii::setPathOfAlias('Less', dirname(__FILE__).'/../vendors/lessphp/lib/Less');
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

		$this->_parser = new \Less\Parser();
	}

	/**
	 * Compiles the less files.
	 * @throws CException if the source path does not exist
	 */
	public function compile()
	{
		foreach ($this->paths as $cssPath => $less)
		{
			$toPath = $this->basePath.'/'.$cssPath;
			if(isset($less['precompile']) and $less['precompile'] and file_exists($toPath)){
				continue;
			}
			
			$css = '';
			foreach($less['paths'] as $fromPath){
				$fromPath = $this->basePath.'/'.$fromPath;
				
				if (file_exists($fromPath))
					$css .= $this->parse($fromPath);
				else
					throw new CException(__CLASS__.': '.Yii::t('less','Failed to compile less file. Source path('.$fromPath.') does not exist!'));				
			}
			file_put_contents($toPath,$css);
			$this->_parser->clearCss();
		}		
	}

	/**
	 * Parses the less code to css.
	 * @param string $filename the file path to the less file
	 * @return string the css
	 */
	public function parse($filename)
	{
		try
		{
			$css = $this->_parser->parseFile($filename);
		}
		catch (\Less\Exception\ParserException $e)
		{
			throw new CException(__CLASS__.': '.Yii::t('less','Failed to compile less file with message: `{message}`.',
					array('{message}'=>$e->getMessage())));
		}

		return $css;
	}
}
