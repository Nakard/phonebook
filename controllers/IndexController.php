<?php

use Phonebook\Repository\PhoneNumberRepository;
use Phonebook\Form\Phonebook_Form_Abstract;

/**
 * Main display and edit controller
 *
 * Class Phonebook_IndexController
 */
class Phonebook_IndexController extends Zend_Controller_Action
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Inits Entity Manager and action contexts
     */
    public function init()
    {
        $registry = Zend_Registry::getInstance();
        $this->entityManager = $registry->entityManager;
        /**
         * @var Zend_Controller_Action_Helper_ContextSwitch $contextSwitch
         */
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch
            ->clearActionContexts('generate')
            ->clearActionContexts('remove')
            ->clearActionContexts('editPerson')
            ->setActionContext('remove', 'json')
            ->setActionContext('editPerson','json')
            ->setActionContext('editNumber','json')
            ->initContext();
        /**
         * @var Zend_Controller_Action_Helper_AjaxContext $ajaxContext
         */
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->clearActionContexts('remove')
            ->clearActionContexts('editPerson')
            ->clearActionContexts('generate')
            ->addActionContext('index','html')
            ->addActionContext('editPerson','html')
            ->addActionContext('editNumber','html')
            ->initContext();
    }

    /**
     * Action for automatic phonebook fill up - for testing
     */
    public function generateAction()
    {
        $randNames = array('Abigail','Adela','Adelina','Adrianna','Agnieszka','Aida','Albina','Aldona','Aleksandra','Alfreda','Alicja','Alina','Alma','Amadea','Amalia','Amelia','Anastazja','Aneta','Angela','Angelina','Aniela','Anita','Anna','Antonina','Apolonia','Ariadna','Asenat','Aurelia','Balbina','Barbara','Batszeba','Beata','Beatrycze','Benita','Berenika','Bernadetta','Berta','Bogna','Bogumiła','Bogusława','Boguwola','Bolesława','Bożena','Bronisława','Brygida','Cecylia','Celina','Cezaryna','Czesława','Dagna','Dalida','Dalila','Damaris','Daniela','Danuta','Daria','Debora','Diana','Dina','Diomeda','Dionizja','Ditta','Dobrawa','Domicela','Dominika','Donata','Doris','Dorota','Edyta','Eleonora','Eliza','Elwira','Elżbieta','Emanuela','Emilia','Erazma','Ernesta','Erwina','Eryka','Estera','Eufemia','Eugenia','Eulalia','Eunika','Ewa','Ewelina','Faustyna','Felicja','Felicyta','Filomena','Flawia','Flora','Florentyna','Franciszka','Fryderyka','Gabriela','Gaudencja','Gertruda','Gilda','Gizela','Gracja','Gracjana','Grażyna','Greta','Halina','Halszka','Helena','Henryka','Hermenegilda','Hilaria','Hildegarda','Honorata','Honoryna','Hortensja','Ida','Iga','Ilona','Ilza','Ingrid','Irena','Iryda','Iwetta','Iwona','Izabela','Izabella','Izolda','Jadwiga','Jagna','Jagoda','Jakobina','Janina','Jarmiła','Jarosława','Jaśmina','Joanna','Jolanda','Jolanta','Jowita','Józefa','Józefina','Judyta','Julia','Julianna','Julita','Justyna','Kaja','Kalina','Kamelia','Kamila','Karena','Karina','Karolina','Katarzyna','Kazimiera','Kesja','Kinga','Kira','Klara','Klaudia','Klementyna','Klotylda','Konstancja','Kordula','Kornelia','Krystyna','Ksenia','Kunegunda','Kwiryna','Larysa','Laura','Lea','Leokadia','Leonia','Lidia','Ligia','Lilianna','Lilla','Liza','Lolita','Lora','Luba','Lucja','Lucyna','Ludmiła','Ludomira','Ludwika','Ludwina','Luiza','Lukrecja','Łucja','Magda','Magdalena','Maja','Malina','Malwina','Małgorzata','Marcela','Marcelina','Marcjanna','Margareta','Maria','Marianna','Marietta','Marika','Mariola','Marlena','Marta','Martyna','Maryla','Maryna','Marzanna','Marzena','Matylda','Melania','Metoda','Michalina','Milena','Mira','Mirabella','Miranda','Miriam','Mirosława','Monika','Mścisława','Nadia','Nadzieja','Natalia','Neomiła','Nina','Noemi','Nora','Ofelia','Oktawia','Olga','Olimpia','Oliwia','Otylia','Ozanna','Patrycja','Paula','Paulina','Pelagia','Petronela','Przybysława','Pryscylla','Pulcheria','Rachela','Ramona','Rebeka','Regina','Renata','Rita','Roberta','Roksana','Roma','Romana','Rozalia','Rozanna','Róża','Rudolfina','Rut','Ryszarda','Sabina','Salome','Salomea','Sandra','Sara','Sawa','Scholastyka','Sewera','Sława','Sławomira','Sonia','Stanisława','Stefania','Stella','Swietłana','Sylwana','Sylwia','Taida','Talita','Tamara','Tatiana','Teodora','Teodozja','Teresa','Urszula','Wacława','Walentyna','Waleria','Wanda','Wera','Weronika','Wiesława','Wiktoria','Wiola','Wioletta','Władysława','Zdzisława','Zefiryna','Zelmira','Zenobia','Zofia','Zuzanna','Zyta','Żaklina','Żalina','Żaneta','Żanna','Aaron','Abdiasz','Abel','Abraham','Adam','Adolf','Adrian','Agenor','Albert','Albrecht','Albin','Aleksander','Aleksy','Alfons','Alfred','Alojzy','Amadeusz','Ambroży','Amos','Anastazy','Anatol','Andrzej','Antoni','Anzelm','Apolinary','Apoloniusz','Arkadiusz','Arnold','Aron','Artur','Atanazy','August','Augustyn','Aureli','Aurelian','Aureliusz','Axel','Barnaba','Barnabasz','Bartłomiej','Bartosz','Bazyli','Benedykt','Beniamin','Benon','Bernard','Bernardyn','Bertold','Błażej','Bogdan','Bogusław','Bogusz','Bohdan','Bolesław','Bonawentura','Bonifacy','Borys','Borysław','Borzysław','Bożydar','Bronimir','Bronisław','Brunon','Cecylian','Celestyn','Cezariusz','Cezary','Cyprian','Cyriak','Cyryl','Czesław','Damazy','Damian','Daniel','Dariusz','Dawid','Dezydery','Dezyderiusz','Dionizy','Dobiesław','Dobromir','Dobromierz','Dobrosław','Domicjan','Dominik','Domosław','Donald','Donat','Dorian','Duszan','Dymitr','Dyzma','Edgar','Edmund','Edward','Edwin','Egbert','Egon','Eliasz','Eligiusz','Elizeusz','Emanuel','Emil','Emilian','Erazm','Ernest','Eryk','Erwin','Eugeniusz','Eustachy','Euzebiusz','Ewald','Ewaryst','Ezdrasz','Ezechiel','Fabian','Faustyn','Felicjan','Feliks','Ferdynand','Fidelis','Filemon','Filip','Flawiusz','Florian','Fortunat','Franciszek','Fryderyk','Gabor','Gabriel','Gaweł','Gedeon','Gerwazy','Gilbert','Gniewomir','Goliat','Gościmił','Gościsław','Gotard','Gracjan','Grzegorz','Gustaw','Gwalbert','Gwidon','Heliodor','Helmut','Henryk','Herbert','Herman','Hieronim','Hilary','Hipolit','Honoriusz','Horacy','Hubert','Hugon','Idzi','Ignacy','Igor','Ildefons','Innocenty','Ireneusz','Irwin','Iwan','Iwo','Izydor','Izaak','Izajasz','Jacek','Jacenty','Jakub','Jan','Janisław','Janusz','Jaromir','Jarosław','Jeremi','Jeremiasz','Jerzy','Jędrzej','Joachim','Joel','Jonasz','Jonatan','Jordan','Jozue','Józef','Juda','Judasz','Julian','Juliusz','Jurand','Justyn','Kacper','Kain','Kajetan','Kalikst','Kamil','Kandyd','Karol','Kazimierz','Kiejstut','Klaudian','Klaudiusz','Klemens','Kleofas','Konrad','Konstancjusz','Konstanty','Konstantyn','Kornel','Korneliusz','Krzysztof','Kryspin','Krystyn','Ksawery','Kwiryn','Lambert','Laurenty','Lech','Leon','Leonard','Leonid','Leopold','Leszek','Longin','Lubomir','Lubor','Lubosław','Lucjan','Lucjusz','Ludomił','Ludomir','Ludosław','Ludwik','Lutomir','Ładysław','Łazarz','Łucjan','Łukasz','Maciej','Makary','Maksym','Maksymilian','Manfred','Mansfet','Marcel','Marceli','Marcelin','Marcin','Marcjal','Marcjan','Marek','Marian','Mariusz','Martynin','Mateusz','Medard','Melchior','Metody','Michał','Micheasz','Mieczysław','Mieszko','Mikołaj','Miłosz','Miromir','Miron','Mirosław','Mojżesz','Myślibor','Nahum','Namysław','Napoleon','Narcyz','Natan','Natanael','Nehemiasz','Nikodem','Noe','Norbert','Odon','Oktawian','Olaf','Olgierd','Onufry','Oswald','Otniel','Ozeasz','Pafnucy','Pankracy','Paschalis','Patrycjusz','Patrycy','Patryk','Paweł','Pelagiusz','Petroniusz','Piotr','Pius','Polikarp','Prokop','Prosper','Protazy','Przemysław','Przybysław','Racibor','Radek','Radomił','Radomir','Radosław','Radowit','Radzimierz','Radzimir','Rafał','Rajmund','Rajnold','Remigiusz','Robert','Roch','Rodryg','Roland','Roman','Romuald','Rościsław','Ruben','Rudolf','Rufin','Ryszard','Salomon','Samson','Samuel','Saturnin','Saul','Sebastian','Sergiusz','Serwacy','Seweryn','Sławoj','Sławomir','Sławosz','Sobiesław','Stanisław','Stefan','Sulimierz','Symeon','Sykstus','Sylweriusz','Sylwester','Sylwan','Sylwin','Sylwiusz','Szczepan','Szczęsny','Szymon','Ścibor','Tadeusz','Telesfor','Teobald','Teodor','Teodozjusz','Teofil','Tobiasz','Tomasz','Tomisław','Tymon','Tymoteusz','Tytus','Urban','Uriasz','Urlyk','Ursyn','Wacław','Waldemar','Walenty','Walerian','Walery','Walter','Wawrzyniec','Wespazjan','Wiaczesław','Wieńczysław','Wiesław','Wiktor','Wiktoryn','Wilhelm','Wincenty','Wirgiliusz','Wit','Witalis','Witold','Witomir','Witosław','Władysław','Włodzimierz','Wodzisław','Wojciech','Wszebor','Wyszomir','Zachariasz','Zbigniew','Zbisław','Zdobysław','Zdzisław','Zenobiusz','Zenon','Ziemowit','Zygfryd','Zygmunt','Żelisław','Żywisław');
        $randSurnames = array('Nowak','Kowalski','Wiśniewski','Dąbrowski','Lewandowski','Wójcik','Kamiński',
            'Kowalczyk','Zieliński','Szymański','Woźniak','Kozłowski','Jankowski','Wojciechowski','Kwiatkowski','Kaczmarek','Mazur','Krawczyk','Piotrowski','Grabowski','Nowakowski','Pawłowski','Michalski','Nowicki','Adamczyk','Dudek','Zając','Wieczorek','Jabłoński','Król','Majewski','Olszewski','Jaworski','Wróbel','Malinowski','Pawlak','Witkowski','Walczak','Stępień','Górski','Rutkowski','Michalak','Sikora','Ostrowski','Baran','Duda','Szewczyk','Tomaszewski','Pietrzak','Marciniak','Wróblewski','Zalewski','Jakubowski','Jasiński','Zawadzki','Sadowski','Bąk','Chmielewski','Włodarczyk','Borkowski','Czarnecki','Sawicki','Sokołowski','Urbański','Kubiak','Maciejewski','Szczepański','Kucharski','Wilk','Kalinowski','Lis','Mazurek','Wysocki','Adamski','Kaźmierczak','Wasilewski','Sobczak','Czerwiński','Andrzejewski','Cieślak','Głowacki','Zakrzewski','Kołodziej','Sikorski','Krajewski','Gajewski','Szymczak','Szulc','Baranowski','Laskowski','Brzeziński','Makowski','Ziółkowski','Przybylski','Domański','Nowacki','Borowski','Błaszczyk','Chojnacki','Ciesielski','Mróz','Szczepaniak','Wesołowski','Górecki','Krupa','Kaczmarczyk','Leszczyński','Lipiński','Kowalewski','Urbaniak','Kozak','Kania','Mikołajczyk','Czajkowski','Mucha','Tomczak','Kozioł','Markowski','Kowalik','Nawrocki','Brzozowski','Janik','Musiał','Wawrzyniak','Markiewicz','Orłowski','Tomczyk','Jarosz','Kołodziejczyk','Kurek','Kopeć','Żak','Wolski','Łuczak','Dziedzic','Kot','Stasiak','Stankiewicz','Piątek','Jóźwiak','Urban','Dobrowolski','Pawlik','Kruk','Domagała','Piasecki','Wierzbicki','Karpiński','Jastrzębski','Polak','Zięba','Janicki','Wójtowicz','Stefański','Sosnowski','Bednarek','Majchrzak','Bielecki','Małecki','Maj','Sowa','Milewski','Gajda','Klimek','Olejniczak','Ratajczak','Romanowski','Matuszewski','Śliwiński','Madej','Kasprzak','Wilczyński','Grzelak','Socha','Czajka','Marek','Kowal','Bednarczyk','Skiba','Wrona','Owczarek','Marcinkowski','Matusiak','Orzechowski','Sobolewski','Kędzierski','Kurowski','Rogowski','Olejnik','Dębski','Barański','Skowroński','Mazurkiewicz','Pająk','Czech','Janiszewski','Bednarski','Łukasik','Chrzanowski','Bukowski','Leśniak','Cieślik','Kosiński','Jędrzejewski','Muszyński','Świątek','Kozieł','Osiński','Czaja','Lisowski','Kuczyński','Żukowski','Grzybowski','Kwiecień','Pluta','Morawski','Czyż','Sobczyk','Augustyniak','Rybak');

        $countNames = count($randNames);
        $countSurnames = count($randSurnames);
        for($i = 1; $i <= 10; $i++)
        {
            $firstName = $randNames[mt_rand(0,$countNames-1)];
            $lastName = $randSurnames[mt_rand(0,$countSurnames-1)];
            $person = new \Phonebook\Entity\Person();
            $person->setCredentials($firstName,$lastName);
            $this->entityManager->persist($person);
        }
        $this->entityManager->flush();


        /**
         * @var \Phonebook\Repository\PersonRepository $personRepo
         */
        $personRepo = $this->entityManager->getRepository('\Phonebook\Entity\Person');
        $persons = $personRepo->findAllIds();
        $personsCount = count($persons);
        for($i = 1; $i <= 50; $i++)
        {
            $randIndex = mt_rand(0,$personsCount-1);
            $randPersonId = (int)$persons[$randIndex]['id'];
            /**
             * @var \Phonebook\Entity\Person $person
             */
            $person = $personRepo->find($randPersonId);
            $randPhonenumber = mt_rand(100000000,999999999);
            $phoneNumber = new \Phonebook\Entity\PhoneNumber();
            $phoneNumber->setPhoneNumber($randPhonenumber);
            $phoneNumber->setPerson($person);
            $person->addPhoneNumber($phoneNumber);
            $this->entityManager->persist($person);
        }
        $this->entityManager->flush();
    }

    /**
     * Display paginated numbers
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $filter = $this->getFilter($request);
        $page = $this->_getParam('page');
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        $session = new Zend_Session_Namespace('Phonebook');
        if($message = $session->message)
        {
            $this->view->sessionMessage = $message;
            unset($session->message);
        }
        /**
         * @var PhoneNumberRepository $phoneNumberRepository
         */
        $phoneNumberRepository = $this->entityManager->getRepository('\Phonebook\Entity\PhoneNumber');
        $numbers = $phoneNumberRepository->getNumbersHydrated($filter);

        $paginator = Zend_Paginator::factory($numbers);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $numbersOnPage = $paginator->getCurrentItems();

        if($request->isXmlHttpRequest())
        {
            $this->_helper->viewRenderer('numbers');
        }

        $this->view->numbers = $numbersOnPage;
        $this->view->paginator = $paginator;
        $this->view->page = $page;
        $this->view->filter = $filter;
    }

    /**
     * Gets filter value from session or POST, depending what's available
     *
     * @param Zend_Controller_Request_Http $request
     */
    protected function getFilter(Zend_Controller_Request_Http &$request)
    {
        $postFilter = $request->getPost('filter');
        $session = new Zend_Session_Namespace('Phonebook');
        if('' === $postFilter || $postFilter)
        {
            $session->filter = $postFilter;
            return $postFilter;
        }
        return $session->filter;
    }

    /**
     * Removes number
     */
    public function removeAction()
    {
        $phoneNumberId = $this->_getParam('id');
        /**
         * @var PhoneNumberRepository $phoneNumberRepository
         */
        $phoneNumberRepository = $this->entityManager->getRepository('\Phonebook\Entity\PhoneNumber');
        $phoneNumber = $phoneNumberRepository->find($phoneNumberId);
        $title = 'Phone number remove';
        try
        {
            $this->entityManager->remove($phoneNumber);
            $this->entityManager->flush();
            $message = 'Phone number id: '.$phoneNumberId.' removed';
            $status = '200';
        }
        catch(\Doctrine\ORM\ORMInvalidArgumentException $e)
        {
            $message = 'There was an error during removing the number from the database';
            $status = '400';
        }

        $json = array(
            'message'   =>  $message,
            'status'    =>  $status,
            'title'     =>  $title,
        );

        $this->_helper->json($json);

    }

    /**
     * Edits number or person, depending on choice
     */
    public function editAction()
    {
        $entityId = $this->_getParam('id');
        $entityResolver = new \Phonebook\Resolver\EntityResolver();
        $entityClass = ucfirst($entityResolver->typeTranslate($this->_getParam('type')));

        /**
         * @var Zend_Controller_Request_Http $request
         */
        $request = $this->getRequest();
        $entityRepository = $this->entityManager->getRepository('\\Phonebook\\Entity\\'.$entityClass);
        $entity = $entityRepository->find($entityId);
        $entityFormClass = "\\Phonebook\\Form\\Phonebook_Form_Edit".$entityClass;
        /**
         * @var Phonebook_Form_Abstract $entityForm
         */
        $entityForm = new $entityFormClass();
        $entityFormSetMethod = 'set'.$entityClass;
        $entityForm->$entityFormSetMethod($entity);
        $title = $entityResolver->getEntityMessage($entityClass, 'title');
        $message = $entityResolver->getEntityMessage($entityClass, 'message');
        $status = '200';
        $formErrors = array();
        $validationClosure = null;
        $validationMethod = $entityResolver->getEntityMessage($entityClass, 'validate');

        if($request->isPost())
        {
            if($entityForm->isValid($request->getPost()))
            {
                try
                {
                    if(!is_null($validationMethod))
                        $validationClosure = array($entityRepository, $validationMethod);
                    $values = $entityForm->getValues();
                    $entityResolver->callEntitySetMethods($entity, $values, $validationClosure);
                    $this->entityManager->persist($entity);
                    $this->entityManager->flush();
                }
                catch(\Exception $e)
                {
                    $this->setExceptionErrorResponse($formErrors,$status,$message,$e);
                }
            }
            else
            {
                $this->setFormErrorResponse($formErrors, $status, $message, $entityForm);
            }
            $this->_helper->json(array(
                'title'         =>  $title,
                'status'        =>  $status,
                'message'       =>  $message,
                'formErrors'    =>  $formErrors,
                'form'          =>  $entityForm->render()
            ));
            return;
        }

        $this->view->modalData = array(
            'title' =>  $title,
            'form'  =>  $entityForm->render()
        );
    }

    /**
     * Sets data for form error json output
     *
     * @param array     $formErrors
     * @param string    $status
     * @param string    $message
     */
    protected function setFormErrorResponse(array &$formErrors, &$status, &$message, Phonebook_Form_Abstract &$form)
    {
        $formErrors = array_merge($formErrors, $form->getMessages());
        $status = '400';
        $message = 'There are errors in the form';
    }

    /**
     * Sets data for exception error json output
     *
     * @param array     $formErrors
     * @param string    $status
     * @param string    $message
     * @param Exception $e
     */
    protected function setExceptionErrorResponse(array &$formErrors, &$status, &$message, \Exception $e)
    {
        $formErrors[] = $e->getMessage();
        $status = '400';
        $message = 'Database error occurred';
    }
}

