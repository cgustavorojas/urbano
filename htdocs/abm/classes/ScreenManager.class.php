<?php
/**
 * @package abm
 */

/**
 * Mantiene una lista de las pantallas que están activas, con el orden en que están. 
 * 
 * Esta clase intenta reproducir el funcionamiento de una aplicación 
 * típica de escritorio con ventanas modales: cada ventana nueva que se abre se posiciona
 * arriba de la última ventana abierta. Cuando una ventana se cierra, se libera de memoria
 * y pasa a estar visible la ventana que estaba inmediatamente debajo. 
 * 
 * La complicación inherente del browser es que el usuario puede "salirse" del orden
 * natural y dejar pantallas colgadas, o volver con el botón "back" a dos pantallas para
 * atrás. Estos casos se resuelven agregando la siguiente lógica: 
 * 
 * 1. Cuando se muestra una ventana que estaba en la capa N, se eliminan todas las ventanas
 *    que estaban en las capas superiores. 
 * 
 * 2. En determinadas situaciones (ej: ventanas llamadas desde el menú principal), se 
 *    limpian todas las ventanas de la pila. 
 * 
 * @package abm 
 */
class ScreenManager
{
	private $screens;
	private $id;
	
	private static $singleton; 

	public function __construct() {
		$this->screens = array();
		$this->id = "sm" . rand();
	}
	
	/**
	 * Guarda una ventana en el stack
	 */
	public function push(Screen $screen) 
	{
		$this->screens[] = $screen; 
	}

	/**
	 * Devuelve la única instancia que debe exister de ScreenManager.
	 * Si no exite, la crea y la guarda en la SESSION. Sucesivas veces, devuelve la que está
	 * guardada ahí. 
	 * 
	 * @return ScreenManager
	 */
	public static function getInstance()
	{
		if (! is_null(ScreenManager::$singleton)) {
			return ScreenManager::$singleton;
		} else if (isset ($_SESSION['screenManager'])) {
			$sm = $_SESSION['screenManager'];
		} else {
			$sm = new ScreenManager(); 
		}
		ScreenManager::$singleton = $sm; 
		return $sm;
	}
	
	/**
	 * Devuelve la cantidad de ventanas en el stack
	 * @return int Cantidad de ventanas (puede ser cero, no NULL)
	 */
	public function getCount()
	{
		return count($this->screens);
	}
	
	/**
	 * Guarda el objeto en la session
	 */
	public function serialize()
	{
		$_SESSION['screenManager'] = $this;
	}
	
	/**
	 * Recupera una ventana del stack. 
	 * Elimina las ventanas que pudieran estar más arriba.
	 *  
	 * @param $id ID de la ventana a recuperar (si es NULL, devuelve la de más arriba)
	 * @return la pantalla pedida o NULL si no existe
	 */
	public function pop($id = NULL)
	{
		if (count($this->screens) == 0)
			return NULL;
			
		if (is_null($id)) { 
			return $this->screens[count($this->screens)-1];
		}
		
		for ($i = count($this->screens) ; $i > 0 ; $i--)
		{
			if ($this->screens[$i-1]->getId() == $id) {
				
				if ($i < count($this->screens)) {
					$this->screens = array_slice ($this->screens, 0, $i);
				}					
				return $this->screens[$i-1];
			}
		}
		return NULL;		
	}
	
	/**
	 * Devuelve una ventana en base a su posición en la pila (a diferencia de otros métodos que piden su ID). 
	 * Si se pasa posicion=0, se devuelve la ventana actual (la que está más arriba en la pila).
	 * Si se pasa un número positivo, se devuelve ese número de ventana empezando desde abajo (1=la más abajo, 2=la segunda, etc.)
	 * Si se pasa un número negativo, se devuelve ese número empezando de arriba, sin contar la actual (-1 = la inmediatamente debajo de la actual, etc.).
	 * 
	 * @param int $posicion 0=Ventana actual, -1 = la inmediatamente debajo, -2 = dos abajo de ésta, 1 = la primera empezan
	 * @return Screen
	 */
	public function get($posicion = 0)
	{
		if ($posicion <= 0) {
			return $this->screens[count($this->screens) + $posicion - 1];
		} else {
			return $this->screens[$posicion - 1];
		}
	}
	
	/**
	 * Devuelve la URL para volver a la ventana que está más arriba de todo.
	 * En la operación normal de cerrado de una ventana, primero habría que llamar a remove($id)
	 * de la ventana que se  quiere cerrar y luego hacer un redirect a lo que devuelva esta función. 
	 * Si no hay más ventanas abajo, devuelve una constante que debería apuntar a la home page.
	 * 
	 * Utiliza Utils::makeAbsoluteUrl() para obtener una ruta absoluta. 
	 * 
	 * @return string URL para volver a la ventana de más arriba
	 */
	public function getReturnUrl()
	{
 		if (count($this->screens) == 0) {
			$path = ''; 
		} else {
			$screen = $this->screens[count($this->screens)-1];
			$path = $screen->getReturnUrl();
		}	
		
		return Utils::makeAbsoluteUrl($path);
	}
	
	/**
	 * Saca de la lista la pantalla seleccionada (y todas las que estuvieran arriba)
	 */
	public function remove($id)
	{
		for ($i = count($this->screens) ; $i > 0 ; $i--)
		{
			if ($this->screens[$i-1]->getId() == $id) {				
				$this->screens = array_slice ($this->screens, 0, $i - 1);
			}
		}		
	}
	
	/**
	 * Elimina todas las pantallas del stack.
	 */
	public function clear()
	{
		$this->screens = array(); 		
	}
	
	public function printStack()
	{
		echo "Server: " . $_SERVER['HTTP_HOST'] . "\n";
		echo "Fecha : " . date('Y-m-d H:i:s') . "\n";

		if (count($this->screens) == 0) { 
			echo "No hay pantallas activas";
			return;
		}
		
		echo "Stack de pantallas:\n";
		for ($i = count($this->screens) ; $i > 0 ; $i--)
		{
			$scr = $this->screens[$i-1];
			if (method_exists($scr, 'getPkValue')) {
				$clase = get_class($scr) . ' [id=' . $scr->getPkValue() . ']';
			} else {
				$clase = get_class($scr);
			}
			echo "   [$i] " . str_pad($clase, 30, ' ', STR_PAD_RIGHT) . ' "' . $scr->getTitulo() . "\"\n"; 			
		}

		$scr = $this->screens[count($this->screens)-1];
		
		echo "Pantalla activa:\n";
		if (! is_null($scr->getError())) {
			echo "   Error    : " . $scr->getError() . "\n";
		}
//		if (method_exists($scr, 'getSql')) {
//			echo "   SQL      : " . $scr->getSql() . "\n";
//		}
		if (method_exists($scr, 'getFiltros')) 
		{
			echo "   Filtros:\n";
			foreach ($scr->getFiltros()->getAll() as $f)
			{
				$v = $f->getValue();
				if (! is_null($v))
				{
					$txt = is_null($f->getTxt()) ? '' :  '("' . $f->getTxt() . '")';
					echo "      " . str_pad($f->getId() . $txt, 25) . " = " . $v . "\n";
				}
			}
		}
		
		if (method_exists($scr, 'getInputs')) 
		{
			echo "   Inputs:\n";
			foreach ($scr->getInputs()->getAll() as $i)
			{
				$v = $i->getValue();
				if (! is_null($v))
				{
					$txt = is_null($i->getTxt()) ? '' :  '("' . $i->getTxt() . '")';
					echo "      " . str_pad($i->getId() . $txt, 25) . " = " . $v . "\n";
				}
			}
		}
		
		if (method_exists($scr, 'getTab'))
		{
			$tab = $scr->getTab(); 
			if (! is_null($tab)) 
			{
				echo "   Tab activo:\n";
				echo "      Título: " . $tab->getTitulo() . "\n";
				echo "      Clase : " . get_class($tab) . "\n";		
			}	
		}
		
		if (method_exists($scr, 'getSelected'))
		{
			echo "   Registros seleccionados: " . implode (' / ', $scr->getSelected()) . "\n"; 
		}
		
		
	}

}