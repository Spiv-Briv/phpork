<?php
return [
    "introduction" => [
        "title" => "Wstęp",
        "content" => "PHPork oferuje model, którego można użyć do łatwego połączenia z bazą danych. Oferuje on ogromną ilość funkcji, których pełną listę znajdziesz w <a href=\"#%s\">tutaj</a>"
    ],
    "create_model" => [
        "title" => "Utworzenie modelu",
        "content" => "Utworzyć model możesz na 2 sposoby. Tworząc plik manualnie w folderze <b>App\\Models</b>. Drugi sposób jest za pomocą polecenia <b>php cli make model</b>. Polecenia to wymaga parametru <b>name</b>. Pozostałe parametry są opcjonalne. Najprostszy przykład polecenia, które utworzy model to: <b>php cli make model -name:MyModel</b>. Po więcej szczegółów przejdź <a href=%s#%s>tutaj</a>.",
    ],
    "static_properties" => [
        "title" => "Statyczne właściwości modelu",
        "content" => "Każdy model, który utworzysz ma swoje statyczne właściwośći. Są to:
            <ul>
                %s
            </ul>",
        "list_item" => "<li style='margin: 5px;'><a href='#%s'>%s</a></li>",
    ],
    "table" => [
        "title" => "Powiązana tabela",
        "content" => "static string \$table \u{2192} Właściwość odpowiadająca za podłączenie modelu do odpowiedniej tabeli. Jest ona obowiązkowa i jej brak spowoduje wyświetlenie błędu, tak samo jak podanie tabeli, która nie istnieje w bazie danych."
    ],
    "restrict_columns" => [
        "title" => "Określanie kolumn",
        "content" => "static array \$columns \u{2192} Właściwość określająca, które kolumny z tabeli zostaną pobrane i zmienione na właściwość modelu. Jest ona obowiązkowa i jej brak spowoduje wyświetlenie błędu. Błąd wystąpi również, jeśli podasz kolumnę, która nie istnieje w tabeli. Tablica kolumn może być pusta, jednak nie będzie z niej żadnego użytku."
    ],
    "properties_type" => [
        "title" => "Typ właściwości",
        "content" => "static array \$types \u{2192} Właściwość wskazująca, jakim typ powinna mieć właściwość określona w kolumnach. Nie musi być ona podawana, gdyż model oferuje domyślne przekształcanie. Jeśli typ właściwości nie zostanie podany, a wartość będzie liczbą, domyślnie zostanie przekształcona na typ <b>int</b>. W przeciwnym wypadku będzie to <b>string</b>. Masz wiele typów, jakie może przyjąć właściwość. Są to:
            <ul>
                <li style='margin: 5px;'>bool \u{2192} Przyjmuje wartośći <b>Prawda</b> lub <b>Fałsz</b>.</li>
                <li style='margin: 5px;'><span>int</span> \u{2192} Przyjmuje liczby całkowite.</li>
                <li style='margin: 5px;'>float \u{2192} Przyjmuje liczby rzeczywiste.</li>
                <li style='margin: 5px;'>string \u{2192} Przyjmuje łańcuch znaków.</li>
                <li style='margin: 5px;'>array \u{2192} Tablica zawierająca łańcuchy znaków (równoznaczne z <b>string[]</b>).</li>
                <li style='margin: 5px;'>datetime \u{2192} Obiekt DateTime, który będzie wyświetlać zarówno datę jak i godzinę.</li>
                <li style='margin: 5px;'>time \u{2192} Obiekt DateTime, który będzie wyświetlać tylko godzinę (Informacja o dacie jest domyślna z obiektu DateTime. Można ją zmienić, jednak nie będzie ona miała wpływu na wartość w bazie danych).</li>
                <li style='margin: 5px;'>date \u{2192} Obiekt DateTime, który będzie wyświetlać tylko datę (Informacja o godzinę jest domyślna z obiektu DateTime. Można ją zmienić, jednak nie będzie ona miała wpływu na wartość w bazie danych).</li>
                <li style='margin: 5px;'>Model \u{2192} Możesz zmieniać typ również na inne zdefiniowane przez ciebie modele. Robisz to poprzez podanie pełnej nazwy modelu.</li>
                <li style='margin: 5px;'>typowane tablice \u{2192} Typy <b>bool</b>, <b>int</b>, <b>float</b> i <b>string</b> mogą być przekształcane na tablice o konkretnym typie. Robisz to poprzez <b>typ[]</b>.</li>
            </ul>
            Przykłady typowania to:
                <div>protected static array \$types = [
                    <p>'column1' => 'bool',</p>
                    <p>'column2' => 'array',</p>
                    <p>'column3' => 'float[]',</p>
                    <p>'column4' => AnotherModel::class,</p>
                ];</div>"
    ],
    "linked_property" => [
        "title" => "Powiązana właściwość",
        "content" => "static string \$linkedProperty \u{2192} Właściwość powiązana z tabelą w bazie danych. Nie jest ona obowiązkowa i nie trzeba się nią przejmować, dopóki nie zmieniasz nazwy kolumny klucza głównego tabeli."
    ],
    "string_tree" => [
        "title" => "Wyświetlane właściwości",
        "content" => "static ?array \$stringTree \u{2192} Właściwość, która wskazuje, które kolumny powinny zostać wyświetlone. Domyślnia wartość to <b>null</b>, co oznacza wyświetlenie wszystkich właściwości modelu. W innym przypadku model wyświetli tylko kolumny podane w tablicy. Tutaj możesz również sformatować kolumny i ich nazewnictwo. Jeżeli indeks tablicy jest zdefiniowany, kolumna będzie nazwana tym indeksem, zamiast nazwą właściwości. W dodatku, gdy typem właściwości jest model, możesz wyciągnąć z niego konkretną właściwość, zamiast wywoływać cały model. Przykładowo: <b>'model.name'</b> pobierze wyłącznie właściwość name z modelu."
    ],
    "collection" => [
        "title" => "Kolekcja modelu",
        "content" => "static string \$collection \u{2192} Właściwość wskazująca, która kolekcja z folderu <b>App\\Collections</b> zostanie utworzona, przy korzystaniu z metod zwracających kolekcję. Domyślnie jest to <b>App\\Collections\\Collection</b>. Jeśli istnieje kolekcja, która nazywa się tak samo jak model (z dopiskiem 'Collection'), to zostanie zwrócona ona, zamiast domyślnej kolekcji"
    ],
    "static_methods" => [
        "title" => "Statyczne metody modelu",
        "content" => "Statyczne metody modelu (nie wymagają instancji modelu) w kolejności alfabetycznej:<ul>
        <li style='margin: 5px'>between(string \$column, string \$min, string \$max) \u{2192} Zwraca wszystkie elementy, których wartość właściwości \$column mieści się pomiędzy \$min (włącznie), a \$max (włącznie)</li>
        <li style='margin: 5px'>create(array \$data, bool \$print = false) Tworzy nawy wpis w tabeli. Parametr \$data to tablica asocjacyjna o formacie <i>kolumna => wartość</i>. Wartość może być tylko typu <b>string</b> lub <b>Model</b>.</li>
        <li style='margin: 5px'>find(mixed \$id) \u{2192} zwraca model, którego <a href='#%s'>%s</a> równa się podanej wartości</li>
        <li style='margin: 5px'>first() \u{2192} zwraca pierwszy model z tabeli</li>
        <li style='margin: 5px'>firstWhere(string \$column, string \$operator, string \$value) \u{2192} zwraca pierwszy model w kolekcji, który zgadza się z porównaniem</li>
        <li style='margin: 5px'>getAll() \u{2192} Zwraca kolekcję wszystkich elementów</li>
        <li style='margin: 5px'>page(\$page) \u{2192} Zwraca podaną stronę z kolekcji wszystkich modeli</li>
        <li style='margin: 5px'>query() \u{2192} Tworzy instancję obiektu QueryBuilder, która automatycznie jest podłączona do tabeli w bazie danych.</li>
        <li style='margin: 5px'>sort(string|array \$properties = 'id', bool|array \$ascending = true) \u{2192} Pobiera wszystkie elementy w tabeli, a następnie sortuje je według podanych parametrów</li>
        <li style='margin: 5px'>where(string \$column, string \$operator, string \$value) \u{2192} Zwraca kolekcję zawierającą tylko elementy podane w parametrach</li>
        </ul>"
    ],
    "methods" => [
        "title" => "Metody modelu",
        "content" => "Metody instancji modelu w kolejności alfabetycznej: <ul>
        <li style='margin: 5px;'>add(string \$property, mixed \$value) \u{2192} dodaje liczbę \$value do właściwości \$property</li>
        <li style='margin: 5px;'>arrayPop(string \$property, int \$length = 1) \u{2192} skraca tablicę o \$length elementów od końca tablicy.</li>
        <li style='margin: 5px;'>arrayShift(string \$property, int \$length = 1) \u{2192} skraca tablicę o \$length elementów od końca tablicy.</li>
        <li style='margin: 5px;'>arrayPush(string \$property, mixed \$value) \u{2192} dodaje wartość \$value na koniec tablicy właściwości \$property</li>
        <li style='margin: 5px;'>arrayUnshift(string \$property, mixed \$value) \u{2192} dodaje wartość \$value na początek tablicy właściwości \$property</li>
        <li style='margin: 5px;'>decrement(string \$property) \u{2192} zmniejsza właściwość \$property o 1</li>
        <li style='margin: 5px;'>delete(bool \$print = false) \u{2192} usuwa wpis z tabeli</li>
        <li style='margin: 5px;'>divide(string \$property, mixed \$value) \u{2192} dzieli właściwość \$property przez \$value</li>
        <li style='margin: 5px;'>get(string \$property) \u{2192} pobiera wartość właściwości podanej w parametrze \$property</li>
        <li style='margin: 5px;'>increment(string \$property) \u{2192} zwiększa właściwość \$property o 1</li>
        <li style='margin: 5px;'>load() \u{2192} wczytuje do modelu wartości z bazy danych</li>
        <li style='margin: 5px;'>modulo(string \$property, mixed \$value) \u{2192} przypisuje resztę z dzielenia właściwości \$property przez \$value</li>
        <li style='margin: 5px;'>multiply(string \$property, mixed \$value) \u{2192} mnoży liczbę \$value przez właściwość \$property</li>
        <li style='margin: 5px;'>save() \u{2192} zapisuje model do tabeli w bazie danych</li>
        <li style='margin: 5px;'>set(string \$property, mixed \$value) \u{2192} zmienia wartość właściwości \$property na \$value</li>
        <li style='margin: 5px;'>strPad(string \$property, int \$length, string \$padString = ' ', int \$padType = STR_PAD_RIGHT) \u{2192} dopasowuje ciąg do określonej długości innym ciągiem</li>
        <li style='margin: 5px;'>strReverse(string \$property) \u{2192} odwraca łańcuch znaków we właściwości \$property</li>
        <li style='margin: 5px;'>subString(string \$property, int \$offset, ?int \$length) \u{2192} przypisuje właściwości \$property fragment ciągu znaków</li>
        <li style='margin: 5px;'>subtract(string \$property, mixed \$value) \u{2192} odejmuje liczbę \$value od właściwości \$property</li>
        <li style='margin: 5px;'>toggle(string \$property) \u{2192} odwraca wartość boolean właściwości \$property</li>
        <li style='margin: 5px;'>update(array \$data, bool \$print = false) \u{2192} aktualizuje dane w tabeli według parametrów podanych w tablicy asocjacyjnej \$data</li>
        </ul>"
    ],
];
