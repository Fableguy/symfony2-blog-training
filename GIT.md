Git on Windows
======================

Maak een account aan op github.
Ga naar je profile.
Kom hier terug met je ssh key zo direct ;)

Install git
--------

Gebruik http://guides.beanstalkapp.com/version-control/git-on-windows.html als backlog.

Installeer git via https://code.google.com/p/msysgit/downloads/list?q=full+installer+official+git

klik de optie run in command line aan en laat bij de volgende stap de bovenste geselecteerd: Checkout Windows-style, commit Unix-style line endings”.

Generate SSH key
--------
Open GitBash vanuit het startmenu/tiles
Type in: ssh-keygen -t rsa
Druk op enter.
De default locatie is goed voor ssh keys. (dit snapt git dan weer)
Gebruik geen PassPhrase en druk gelijk 2 keer op enter. (ook al zegt hij dat je een sterkte passphrase moet gebruiken)

Ga naar de map waar je de ssh keys heb staan (staat nog in je terminal)
Open id_rsa.pub met kladblok en kopieer de inhoud.
Ga naar github en je profile en klik op SSH Keys.
Druk op Add ssh key
geef als titel de naam van je pc. (herkenbare naam)
Plak de ssh key in het key veld en druk op add key.

U heeft zojuist git op uw pc de mogelijkheid gegeven om naar github repositories te commiten/pushen.
