<?php
/**
 * LessCompilationBehavior class file.
 * @author Greg Molnar
 * @copyright Copyright &copy; Greg Molnar
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

class LessCompilationBehavior extends CBehavior
{
    /**
     * Declares events and the corresponding event handler methods.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
	public function events()
	{
		return array(
			'onBeginRequest'=>'beginRequest',
		);
	}

	/**
	 * Actions to take before doing the request.
	 */
	public function beginRequest($event)
	{
		if(preg_match('/(.*)+\.css\?*(.+)*$/', $_SERVER['REQUEST_URI'])){
			$this->owner->lessCompiler->compile($_SERVER['REQUEST_URI']);
		}
	}
}
