<?php
final class dbHandler
{    
    public function selectAll()
    {
        try
        {
            //Maak een nieuwe PDO
            $dataSource = "localhost"; //Hier dient je connection string te komen mysql:dbname=;host=;
            $username = "root";
            $password = "";
            $database =  "wordle";
    
            $conn = new PDO("mysql:host=$dataSource;dbname=$database", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
            //Maak gebruik van de prepare functie van PDO om alle woorden op te halen met bijbehorende categorie (Join)
            $Woorden = $conn ->query('SELECT * FROM word INNER JOIN category ON category.categoryid = word.categoryid;');
            
            $wordArray = array();
            //Voer het statement uit
            foreach($Woorden as $Word)
            {
                $wordArray[] = array(
                    'wordId' => $Word['wordId'],
                    'name' => $Word['name'],
                    'text' => $Word['text']
                );
            }
            //Return een associatieve array met alle opgehaalde data.
            return $wordArray;
        }
        catch(PDOException $exception)
        {
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            echo "Connection failed: " . $exception->getMessage();
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
            return null;
        }
    }

    public function selectCategories()
    {
        try{
            //Hier doe je grootendeels hetzelfde als bij SelectAll, echter selecteer je alleen alles uit de category tabel.
            //Maak een nieuwe PDO
            $dataSource = "localhost"; //Hier dient je connection string te komen mysql:dbname=;host=;
            $username = "root";
            $password = "";
            $database =  "wordle";
    
            $conn = new PDO("mysql:host=$dataSource;dbname=$database", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";

            $Categories = $conn ->query('SELECT * FROM category');

            $CategoryArray = array();
            //Voer het statement uit
            foreach($Categories as $Category)
            {
                $CategoryArray[] = array(
                    'categoryId' => $Category['categoryId'],
                    'name' => $Category['name'],
                );
            }
            //Return een associatieve array met alle opgehaalde data.
            return $CategoryArray;


        }
        catch(PDOException $exception)
        {
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            echo "Connection failed: " . $exception->getMessage();
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
            return null;
        }
    }

    public function selectOne($wordId)
    {
        try
        {
            //maak een variabele $rows met een associatieve array met alle opgehaalde data.
            //we willen enkel 1 resultaat ophalen dus zorg dat de eerste regel van de array wordt gereturned.
            $dataSource = "localhost"; 
            $username = "root";
            $password = "";
            $database =  "wordle";
    
            $conn = new PDO("mysql:host=$dataSource;dbname=$database", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";

            //Maak gebruik van de prepare functie van PDO om een select uit te voeren van 1 woord. Degene met het opgegeven Id
            //Let op dat de categorie wederom gejoined moet worden, en de wordId middels een parameter moet worden gekoppeld.
            //Voer het statement uit
            $SelectedWord = $_POST['wordId'];

            $sql = $conn ->prepare('SELECT * FROM word INNER JOIN category ON category.categoryid = word.categoryid WHERE wordId = :wordId;');

            $sql ->bindParam(':wordId', $SelectedWord);

            $sql->execute();

            $selected_Word = $sql->fetch(PDO::FETCH_ASSOC);
            
            return  $selected_Word;

        }
        catch(PDOException $exception)
        {
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            echo "Connection failed: " . $exception->getMessage();
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
            return null;
        }
    }

    public function createWord($text, $categoryId)
    {
        try
        {
            //Maak een nieuwe PDO
            $dataSource = "localhost"; 
            $username = "root";
            $password = "";
            $database =  "wordle";
    
            $conn = new PDO("mysql:host=$dataSource;dbname=$database", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully ";

            //Maak gebruik van de prepare functie van PDO om een insert into uit te voeren op de tabel word.
            //De kolommen die gevuld moeten worden zijn text en categoryId
            $statement = $conn ->prepare('INSERT INTO word (text, categoryId) VALUES (:text , :categoryId)');

            //Gebruik binding om de parameters aan de juiste kolommen te koppellen
            $statement ->bindParam(':text' ,$text);
            $statement ->bindParam(':categoryId', $categoryId);

            //Voer het statement uit
            $statement ->execute();

            //Return een associatieve array met alle opgehaalde data.
            $DataInput = array(
                'text' => $text,
                'categoryId' => $categoryId
            );

            //Voer de query uit en return true omdat alles goed is gegaan
            return $DataInput;
        }
        catch(PDOException $exception)
        {
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            echo "Connection failed: " . $exception->getMessage();
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
            return null;
        }
    }

    public function updateWord($wordId, $text, $category)
    {
        try
        {
            //Maak een nieuwe PDO
            $dataSource = "localhost"; 
            $username = "root";
            $password = "";
            $database =  "wordle";
    
            $conn = new PDO("mysql:host=$dataSource;dbname=$database", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully ";
            //Maak gebruik van de prepare functie van PDO om een update uit te voeren van 1 woord. Degene met het opgegeven Id
            //Let op dat zowel de velden die je wilt wijzigen (categorie en text) met parameters gekoppeld moeten worden
            //De wordId gebruik je voor een WHERE statement.
            $sql = $conn->prepare('UPDATE word SET text = :text, categoryId = :categoryId WHERE wordId = :wordId;');

            //bind alle 3 je parameters
            $sql->bindParam(':text', $text);
            $sql->bindParam(':categoryId', $category);
            $sql->bindParam(':wordId', $wordId);

            //voer de query uit en return true.
            $sql->execute();

            return true;
        }
        catch(PDOException $exception)
        {
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            echo "Connection failed: " . $exception->getMessage();
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
            return null;
        }
    }

    public function deleteWord($id)
    {
        try
        {
            //Maak een nieuwe PDO
            $dataSource = "localhost"; 
            $username = "root";
            $password = "";
            $database =  "wordle";
    
            $conn = new PDO("mysql:host=$dataSource;dbname=$database", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully ";
            //Maak gebruik van de prepare functie van PDO om een delete uit te voeren van 1 woord. Degene met het opgegeven Id
            //De wordId gebruik je voor een WHERE statement.
            $sql = $conn->prepare('DELETE FROM word WHERE wordId = :wordId;');

            //bind je parameter
            $sql->bindParam(':wordId', $id);

            //voer de query uit en return true.
            $sql->execute();
            return true;
        }
        catch(PDOException $e)
        {
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
        }
    }
}
?>