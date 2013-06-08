<?php  
 
$books = array('The Great Gatsby','The Great Combinator','Honey Pooh','Alice in Wonderland','Alice in Chains Taking Over The World');
 
$book_name = $_POST['book_name'];  

foreach ($books as $book)
	if (strstr($book,$book_name))
	   echo "<p>".$book."</p>"; 
  
?> 