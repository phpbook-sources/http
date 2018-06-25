<?php namespace PHPBook\Http;

abstract class Export {
    
    public static function xml($data, $domElement = null, $DOMDocument = null): String {
		
		if (is_null($DOMDocument)) {
			
			$DOMDocument =new \DOMDocument;
			
			$DOMDocument->formatOutput = true;
			
			Static::xml($data, $DOMDocument, $DOMDocument);
			
			return $DOMDocument->saveXML();
			
		} else {
			
			if (is_object($data)) {
			  $data = get_object_vars($data);
			};
			
			if (is_array($data)) {
				
				foreach ($data as $index => $dataElement) {
					
					if (is_int($index)) {
						
						if ($index === 0) {
							
							$node = $domElement;
						}
						
						else {
							
							$node = $DOMDocument->createElement($domElement->tagName);
							
							$domElement->parentNode->appendChild($node);
						}
						
					} else {
						
						$plural = $DOMDocument->createElement($index);
						
						$domElement->appendChild($plural);
						
						$node = $plural;
						
						if (!(rtrim($index, 's') === $index)) {
							$singular = $DOMDocument->createElement('item');
							$plural->appendChild($singular);
							$node = $singular;
						}
						
					};
					
					Static::xml($dataElement, $node, $DOMDocument);
					
				};
				
			} else {
				
				$data = is_bool($data) ? ($data ? 'true' : 'false') : $data;
				
				$domElement->appendChild($DOMDocument->createTextNode($data));
				
			};
			
        };
		
    }

    public static function json($data): String {

       return json_encode($data, JSON_PRETTY_PRINT);
	   
	}

}
