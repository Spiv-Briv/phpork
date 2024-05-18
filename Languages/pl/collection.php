<?php
return [
    "introduction" => [
        "title" => "Wstęp",
        "content" => "Razem z modelem, PHPork daje możliwość utworzenia kolekcji, która jest tablicą dla modeli. Pozwala ona na łatwiejszą manipulację, niż gdybyś użył zwykłej tablicy."
    ],
    "create_collection" => [
        "title" => "Utworzenie kolekcji",
        "content" => "Kolekcję można utworzyć na 2 sposoby. Tworząc plik manualnie w folderze App\\Collections. Drugi sposób to wykorzystanie polecenia <b>php cli make collection</b>. Wymaga ono parametru <b>name</b>. Pozostałe parametry są opcjonalne. Pamiętaj, że podając nazwę kolekcji, nie musisz dopisywać na końcu <b>Collection</b>. Jest to robione automatycznie. Najprostszy przykład polecenia tworzącego kolekcję to: <b>php cli make collection -name:MyModel</b>. Po więcej szczegółów przejdź <a href='%s#%s'>tutaj</a>."
    ],
    "properties" => [
        "title" => "Właściwości kolekcji",
        "content" => "Wszystkie kolekcje mają swoje właściwości, które można zmienić. Są to: <ul>%s</ul>",
        "list_item" => "<li style='margin: 5px;'><a href='#%s'>%s</a></li>",
    ],
    "type" => [
        "title" => "Typ kolekcji",
        "content" => "?string \$type \u{2192} Typ kolekcji określa model, który znajduje się w kolekcji. Domyślna wartość standardowej kolekcji to <b>null</b>, co oznacza, że do tej kolekcji można dołączyć każdy dostępny model. Gdy pierwszy model zostaje dodany do instancji kolekcji, każdy następny element musi być tym samym typem. Tworząc swoją kolekcję, możesz zdefiniować domyślny typ kolekcji podając jako parametr nazwę modelu, np. <b>MyModel::class</b>"
    ],
    "elements_per_page" => [
        "title" => "Paginacja",
        "content" => "int \$elementsPerPage \u{2192} Określa ilość elementów na stronę przy wywoływaniu metody page()."
    ],
    "static_methods" => [
        "title" => "Statyczne metody kolekcji",
        "content" => "Aktualnie kolekcja sama w sobie nie ma statycznych metod"
    ],
    "methods" => [
        "title" => "Metody kolekcji",
        "content" => "Metody instancji modelu w kolejności alfabetycznej: <ul>
        <li style='margin: 5px;'>get(?int \$length, int \$offset) \u{2192} zwraca nową kolekcję o rozmiarze \$length zaczynając od elementu \$offset.</li>
        <li style='margin: 5px;'>limit(?int \$length, int \$offset) \u{2192} ogranicza elementy w kolekcji do rozmiaru \$length zaczynając od elementu \$offset.</li>
        <li style='margin: 5px;'>page(int \$page) \u{2192} dokonuje paginacji i zwraca stronę podaną w paramaterze \$page (pierwsza strona to 0).</li>
        <li style='margin: 5px'>where(string \$column, string \$operator, string \$value) \u{2192} Usuwa z kolekcji elementy niepasujące do podanych parametrów</li>
        <li style='margin: 5px;'>first() \u{2192} zwraca pierwszy element z kolekcji lub null, jeśli kolekcja jest pusta</li>
        <li style='margin: 5px;'>last() \u{2192} zwraca ostatni element z kolekcji lub null, jeśli kolekcja jest pusta</li>
        <li style='margin: 5px;'>between(string \$property, mixed \$min, mixed \$max) \u{2192} ogranicza ilość elementów do tych, których właściwość \$property ma wartość pomiędzy \$min (włącznie) oraz \$max (włącznie)</li>
        <li style='margin: 5px;'>sum(string \$property) \u{2192} zwraca sumę właściwości \$property modelu.</li>
        <li style='margin: 5px;'>avg(string \$property, ?int \$round = null) \u{2192} zwraca średnią wartość właściwości \$property wszystkich elementów w kolekcji zaokrągloną do \$round miejsc po przecinku</li>
        <li style='margin: 5px;'>sort(string|array \$properties = 'id', bool|array \$ascending = true) sortuje kolekcję według właściwości podanych w \$properties. \$ascending to tablica określająca, czy właściwość ma być sortowana rosnąco (true), czy malejąco (false). Przykład: <b>\$collection->sort(['points','wins','id'],[false, false, true]);</b> W tym przykładzie elementy w kolekcji są sortowane według właściwości modelu: <ol><li>points - malejąco</li><li>wins - malejąco</li><li>id - rosnąco</li></ol></li>
        <li style='margin: 5px;'>merge(Collection \$collection) \u{2192} Łączy ze sobą dwie kolekcje. Modele, które już występują w kolekcji nie są dodawane drugi raz.</li>
        <li style='margin: 5px;'>diff(Collection \$collection) \u{2192} Usuwa z kolekcji wszystkie modele, które występują w kolekcji \$collection</li>
        <li style='margin: 5px;'>has(Model \$model) \u{2192} Sprawdza, czy model występuje już w kolekcji.</li>
        <li style='margin: 5px;'>swap(int \$index1, int \$index2) \u{2192} zamienia ze sobą elementy w kolekcji</li>
        </ul>"
    ],
];
