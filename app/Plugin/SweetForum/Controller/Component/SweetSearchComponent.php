<?php
class SweetSearchComponent extends Component {
    function dropBackWords($word) {
		$reg = "/(ый|ой|ая|ое|ые|ому|а|о|у|е|ого|ему|и|ство|ых|ох|ия|ий|ь|я|ы|он|ют|ат)$/i";
		$word = preg_replace($reg,'',$word); //убиваем окончания
		return $word;	
	}

	function eraseSpecSymbols($word) {
		$word = preg_replace('/[^a-zA-ZА-Яа-я0-9-]/ui',' ',$word);
		return preg_replace('/\s/',' ', $word);
	}

	function processString($word) {
		$word = $this->cleanWord($word);
		$word = $this->eraseSpecSymbols($word);
		$word = $this->dropBackWords($word);
		return $word;
	}

	function cleanWord($word) {		
		return mb_strtolower(trim(htmlentities($word, ENT_QUOTES, "UTF-8")));
	}

    /*
    *   @about Функция для создания массива условий
    *   @param string $query - строка запроса
    *   @param string $field - поле в таблице по которому нужно сделать поиск
    */
    function makeConditionsArray($query, $field) {
        $query = $this->processString($query);
        $query = explode(" ", $query);		

        $conds = array();
        foreach($query as $string) $conds[] = array("{$field} LIKE" => "%".$this->processString($string)."%");
        return $conds;
    }
}