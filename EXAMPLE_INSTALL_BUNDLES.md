Bundles
======================
Hola,

Hier de volgende stappen voor na het installeren van symfony2.
We gaan beginnen met het genereren van een symfony2 bundle om mee te kunnen werken.

Ga in de command line naar je blog applicatie.
Bij mij is dat cd /wamp/www/blog

Voer hier het volgende commando uit om de AccountBundle in je src/Blog map te genereren.

    php app/console generate:bundle --namespace=Blog/AccountBundle --format=xml.

Hij vraagt vervolgens een paar vragen.
De antwoorden hierop zijn:
Druk enter (de default name staat al goed)
C:/wamp/www/blog/src bij mij. zorg dat dit pad klopt, het kan bydefault goed staan.
Druk enter (je wilt geen extra default dingen)
Druk enter (yes)
Druk enter (yes)
Druk enter (yes)

Verwijder vervolgens het Acme mapje uit de src map.

Open je IDE ( Integrated Development Environment ) zoals PHPStorm
Open de folder in je IDE van je blog mapje in wamp/www.
Open app/AppKernel.php en verwijder de regel van de acmedemobundle en zie dat je AccountBundle erbij staat.

        $bundles[] = new Acme\DemoBundle\AcmeDemoBundle();

Open app/config/routing_dev.yml en verwijder de onderste verwijzing naar de acmedemobundle

    # AcmeDemoBundle routes (to be removed)
    _acme_demo:
    resource: "@AcmeDemoBundle/Resources/config/routing.yml"

Open app/config/routing.yml en voeg de volgende routing toe:

    blog_account_bundle:
        resource: "@BlogAccountBundle/Resources/config/routing.yml"

Dit zorgt ervoor dat de routing van je nieuwe bundle wordt geregistreerd.

Ga in je IDE naar de src/Blog/AccountBundle/Resources/config map
Rename routing.xml naar routing.yml door rechtermuisknop > refactor > rename of shift + f6 te doen. Je mag beide vinkjes uitvinken van de rename actie.

Wijzig de inhoud van de routing.yml naar:

    blog_accountbundle.controller.security_controller:
        resource: "@BlogAccountBundle/Controller/SecurityController.php"
        type:     annotation

zorg ervoor dat bij het inspringende gedeelte van resource en type altijd 4 spaties voor zitten(indentation). pas dit zo nodig aan in je IDE dat hij dit automatisch doet met yml bestanden.
Met deze routing opzet geef je aan dat hij in de controller moet kijken naar documentatie blocks(docblocks) boven een functie in de controller om te resolven wat zijn route is. Zo'n docblock onderdeel heet ook wel een Annotation.

We gaan eerst een admin gedeelte maken voor onze blog site waar we kunnen inloggen en later de blog artikelen kunnen maken.

We gaan naar de map src/Blog/AccountBundle/Controller en dan renamen we de DefaultController in SecurityController.php
Vergeet hem niet te openen en de class name ook te veranderen in SecurityController:

    class SecurityController extends Controller

Wanneer alle class names overeenkomen met hun bestandsnamen zal de auto loader van php automatisch de classnames kunnen aanroepen :D vet handig :D

Vervang vervolgens in de SecurityController de hele indexAction met de volgende functie:

    /**
    * @Route("/login", name="blog_admin.security.login")
    */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'BlogAccountBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }

Dit is gebaseerd op de volgende url: http://symfony.com/doc/current/book/security.html#book-security-form-login voor meer informatie.

Vergeet niet om de verschillende gebruikte classes, zoals Request, te usen in je SecurityController.
Dit kan door met je muis op Request te klikken en op alt + enter te drukken om vervolgens de Symfony\Component\HttpFoundation\Request te selecteren uit het omhoog gekomen menu.
Je kunt ook handmatig bovenin bij alle use statement de volgende use statements toevoegen:

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Security\Core\SecurityContext;

Voeg ook de volgende use statement toe om te zorgen dat de routing straks goed gaat:

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

Dit geeft aan dat in deze controller die classes gebruikt worden. Wanneer je de applicatie runt gaat hij eerst die bestanden voor je includen zodat je er gebruik van kunt maken.

Zorg dat de volgende docblock boven de loginAction staat:

    /**
     * @Route("/login", name="blog_admin.security.login")
     */
    public function loginAction(Request $request)

Ga vervolgens naar de src/Blog/AccountBundle/Resources/views map en rename de Default map in Security. Rename vervolgens index.html.twig in login.html.twig

Open login.html.twig en vervang alles met:

    {% if error %}
        <div>{{ error.message }}</div>
    {% endif %}

    <form action="{{ path('login_check') }}" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="_username" value="{{ last_username }}" />

        <label for="password">Password:</label>
        <input type="password" id="password" name="_password" />

        <input type="submit" name="login" />
    </form>

Weer van dezelfde site gejat en aangepast :p

Maak vervolgens op dezelfde manier als hierboven een nieuwe bundle aan met het volgende commando:

    php app/console generate:bundle --namespace=Blog/AdminBundle --format=xml.

Verander waar nodig de naam van dingen die bijv eerst BlogAccountBundle waren.
Doorloop dezelfde stappen en pas opnieuw de routing aan.
Rename de controller nu echter naar AdminController en laat de indexAction staan.
Verander de Default map nu dus in Admin. laat de index.html.twig staan, dat klopt nu wel.
Voeg boven de AdminController class de volgende docblock toe:

    /**
     * @Route("/admin")
     */
     class AdminController extends Controller

Voeg boven de indexAction de volgende docblock toe:

    /**
     * @Route("/", name="blog_admin.admin.index")
     */
    public function indexAction()

Verwijder $name als argument uit de indexAction zoals hierboven.
Voeg de volgende use statement boven in toe bij de use statements:

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

Verander de inhoud van de indexAction naar Admin en verander $name in jouwnaam zoals:

    return $this->render('BlogAdminBundle:Admin:index.html.twig', array('name' => 'jouwnaam'));

Nu hebben we een plek gecreëerd waar hij naar toe kan gaan wanneer je bent ingelogd :)
Vervolgens gaan we de security verder inrichten.

Voeg de volgende routes toe aan je app/config/routing.yml bestand:

    login_check:
        path: /admin/login_check

    logout:
        path: /admin/logout

Open vervolgens je app/config/security.yml bestand.
Zorg dat hij er zo uit ziet:

    security:
        encoders:
            Symfony\Component\Security\Core\User\User: plaintext

        role_hierarchy:
            ROLE_ADMIN:       ROLE_USER
            ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

        providers:
            in_memory:
                memory:
                    users:
                        user:  { password: userpass, roles: [ 'ROLE_USER' ] }
                        admin: { password: adminpass, roles: [ 'ROLE_ADMIN' ] }

        firewalls:
            dev:
                pattern:  ^/(_(profiler|wdt)|css|images|js)/
                security: false

            login_firewall:
                pattern: ^/login$
                anonymous: ~

            secured_area:
                pattern:   ^/admin/
                form_login:
                    login_path: blog_admin.security.login
                    check_path: login_check
                logout:
                    path:   /admin/logout
                    target: /

        access_control:
            - { path: ^/admin/, roles: ROLE_ADMIN }

Wat hier staat beschreven is dat er plaintext users in dit bestand staan. Namelijk user en admin met een bijbehorend password wat u zelf eventueel mag wijzigen/toevoegen.
De dev firewall is voor standaard development open gezet. lief he?
De login_firewall zorgt ervoor dat iedereen(een anonymous iemand) naar de /login page mag.
De secured_area geeft aan dat als je naar iets met /admin/ gaat je dan een form login krijgt.
De login_path hiervan hebben wij gedefinieerd in onze security controller.
De check_path is magic van symfony2.
Wanneer we willen uitloggen gaan we naar /admin/logout en moet hij je redirecten naar de homepage(/).
Access_control geeft aan wat de rechten zijn voor een bepaald path.

Nu kunnen we het gaat testen!
Zorg eerst dat je op de command line het volgende commando uitvoert in je map:

    php app/console cache:clear

Wanneer hij geen errors geeft kun je de website openen en bijv naar matthijs.blog/app_dev.php/admin/ gaan. Je ziet dat je geredirect wordt naar /login.
Hier vul je admin en adminpass in en druk op enter.
Als het goed is krijg je de pagina te zien: hallo matthijs!
Dit betekent dat je bent ingelogd.
Als je nu naar /admin/logout gaat wordt je geredirect naar de homepage (/).
Deze bestaat echter niet... dat zal hij vriendelijk aangeven :P

Mocht je wel errors krijgen bij het runnen van het cache:clear commando, probeer dan zelf te achterhalen wat je vergeten bent te doen.
Mocht je errors krijgen bij het laden van de site dan kan het zijn dat hij je cache niet goed gecleared heeft.
Ga dan naar je app/cache/dev/ map en gooi alles daar binnen handmatig weg. Ververs vervolgens je pagina om te kijken of het heeft gewerkt.

Dit ziet er natuurlijk niet uit, maar opzich kan je gewoon html tikken in het index.html.twig bestandje van je Admin bundle om iets leuks te maken.
Je kunt verdere documentatie gebruiken om met deze start verder te gaan ;)

