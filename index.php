<?php
	header('Content-Type: application/json');

	$dom = new DOMDocument();
	@$dom->loadHTMLFile('http://octodex.github.com/');

	$xpath = new DOMXpath($dom);
	$elements = $xpath->query("//*/div[@class='item-shell']");

	if (!is_null($elements)) {
		$octodex = array();
		
		foreach ($elements as $element) {
			$octocat = array();
			
			$tempDom = new DOMDocument();
			$tempDom->appendChild($tempDom->importNode($element, true));
			$tempDom->saveHTML();
			
			$links = $tempDom->getElementsByTagName('a');
			$images = $tempDom->getElementsByTagName('img');
		
			$octocatName = $links->item(2)->nodeValue;
			$octocatPage = $links->item(1)->getAttribute('href');
			$octocatImage = $images->item(0)->getAttribute('data-src');
			$octocatAuthor = $links->item(1)->getAttribute('href');
			$octocatNumber = $tempDom->getElementsByTagName('p')->item(0)->nodeValue;
			$octocatAuthorURL = $links->item(3)->getAttribute('href');
			$octocatAuthorAvatar = $images->item(1)->getAttribute('src');
			
			$octocat['name'] = $octocatName;
			$octocat['page'] = $octocatPage;
			$octocat['image'] = $octocatImage;
			$octocat['author'] = $octocatAuthor;
			$octocat['number'] = str_replace('#', '', $octocatNumber);
			$octocat['authorURL'] = $octocatAuthorURL;
			$octocat['authorAvatar'] = $octocatAuthorAvatar;
			
			/* 
				This is to remove all of the parameters passed to Gravatar, but I don't think that's necessary, as it gives a pretty big image...
	
				$octocat['authorAvatar'] = substr($octocatAuthorAvatar, 0, strpos($octocatAuthorAvatar,'?'));
			*/
			
			$octodex[] = $octocat;
			// var_dump($dom->saveHTML($element));
		}
	}
		
	if (array_key_exists('random', $_GET)) {
		// Though this is not the most efficient way to grab a random octocat, it is not significantly slower, so I'll leave it for the time being
		echo json_encode($octodex[array_rand($octodex)]);
	}
	else echo json_encode($octodex);
?>