Login by account entity
======================
Maak een Entity map in je AccountBundle.
Maak daarbinnen een Account.php aan met de volgende content:
Gebaseer op: http://symfony.com/doc/current/cookbook/security/entity_provider.html

    <?php

    namespace Blog\AccountBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Security\Core\Role\Role;
    use Symfony\Component\Security\Core\User\UserInterface;

    /**
     * Acme\UserBundle\Entity\User
     *
     * @ORM\Table(name="accounts")
     * @ORM\Entity(repositoryClass="Blog\AccountBundle\Repository\AccountRepository")
     */
    class Account implements UserInterface, \Serializable{

        /**
         * @ORM\Column(type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=25, unique=true)
         */
        private $username;

        /**
         * @ORM\Column(type="string", length=64)
         */
        private $password;

        /**
         * @ORM\Column(type="string", length=60, unique=true)
         */
        private $email;

        /**
         * @ORM\Column(name="is_active", type="boolean")
         */
        private $isActive;

        public function __construct()
        {
            $this->isActive = true;
        }

        /**
         * @param mixed $email
         */
        public function setEmail($email)
        {
            $this->email = $email;
        }

        /**
         * @return mixed
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * @param mixed $id
         */
        public function setId($id)
        {
            $this->id = $id;
        }

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param mixed $isActive
         */
        public function setIsActive($isActive)
        {
            $this->isActive = $isActive;
        }

        /**
         * @return mixed
         */
        public function getIsActive()
        {
            return $this->isActive;
        }

        /**
         * @param mixed $password
         */
        public function setPassword($password)
        {
            $this->password = $password;
        }

        /**
         * @return mixed
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * @param mixed $username
         */
        public function setUsername($username)
        {
            $this->username = $username;
        }

        /**
         * @return mixed
         */
        public function getUsername()
        {
            return $this->username;
        }

        /**
         * Returns the roles granted to the user.
         *
         * <code>
         * public function getRoles()
         * {
         *     return array('ROLE_USER');
         * }
         * </code>
         *
         * Alternatively, the roles might be stored on a ``roles`` property,
         * and populated in any number of different ways when the user object
         * is created.
         *
         * @return Role[] The user roles
         */
        public function getRoles()
        {
            return array('ROLE_ADMIN');
        }

        /**
         * Returns the salt that was originally used to encode the password.
         *
         * This can return null if the password was not encoded using a salt.
         *
         * @return string|null The salt
         */
        public function getSalt()
        {
            return null;
        }

        /**
         * Removes sensitive data from the user.
         *
         * This is important if, at any given point, sensitive information like
         * the plain-text password is stored on this object.
         */
        public function eraseCredentials()
        {
            return null;
        }

        /**
         * @see \Serializable::serialize()
         */
        public function serialize()
        {
            return serialize(array(
                    $this->id,
                    $this->username,
                    $this->password
                ));
        }

        /**
         * @see \Serializable::unserialize()
         */
        public function unserialize($serialized)
        {
            list (
                $this->id,
                $this->username,
                $this->password
                ) = unserialize($serialized);
        }

    }

Maak ook een AccountRepository class in een Repository mapje net zoals je bij Account in Entity heb gedaan.

    <?php

    namespace Blog\AccountBundle\Repository;

    use Doctrine\ORM\EntityRepository;

    class AccountRepository extends EntityRepository{

    }

Run vervolgens het volgende commando op de command line in je project er vanuit gaande dat je dit nog niet heb gedaan:

    php app/console doctrine:schema:create

Anders is het:

    php app/console doctrine:schema:update --force

Ga naar je Database toe via bijvoorbeeld localhost/phpmyadmin
Ga naar je blog database en click op de accounts tabel
Click vervolgens op de invoegen tab.
Voeg een account toe.
Voor het wachtwoord moet je bijv. naar http://www.sha1-online.com/
Voer daar je test wachtwoord in.
Mijn wachtwoord 'kaas' resulteerde in: f1fe2f1a3b8deaaa4a219653480f6c3d2140b9ab
Vergeet niet is_active op 1 te zetten.

Open vervoglens app/config/security.yml.
Zorg dat hij er zo uit ziet:

    security:
        encoders:
            Blog\AccountBundle\Entity\Account:
                algorithm:        sha1
                encode_as_base64: false
                iterations:       1

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

    providers:
        administrators:
            entity: { class: BlogAccountBundle:Account, property: username }

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

Dit zorgt ervoor dat hij je Account entity gebruikt om in te loggen.
Daar staat hard gecodeerd dat je ROLE_ADMIN bent dus dat is top! :P
sha1 hash is om het makkelijk zelf toe te voegen zonder registratie formulier...
Later zal je Bcrypt gebruiken met een registratie formulier.