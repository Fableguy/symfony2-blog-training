Login by account entity
======================
Create an Entity folder in your AccountBundle.
Within that, create an Account.php with the following content:

Based on: http://symfony.com/doc/current/cookbook/security/entity_provider.html

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

Now create an AccountRepository class in a Repository folder, just like you did with the Account class in the Entity folder.
Add this as content:

    <?php

    namespace Blog\AccountBundle\Repository;

    use Doctrine\ORM\EntityRepository;

    class AccountRepository extends EntityRepository{

    }
Run the following command on the command line. Make sure you are still in your project folder:

    php app/console doctrine:schema:create

If you had already done that once, use the following command:

    php app/console doctrine:schema:update --force

Go to your database by going to localhost/phpmyadmin.
Go to your blog database and click on the accounts table.
Click on the insert tab.
Add an account.
For the password, you should go to something like: http://www.sha1-online.com/
Fill in your test password over there.
My password 'kaas' was: f1fe2f1a3b8deaaa4a219653480f6c3d2140b9ab
Don't forget to put is_active on 1.

Now open app/config/security.yml
Make sure it looks like this:

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

This makes sure that the Account entity is used when logging in.
ROLE_ADMIN is hard coded, so that is cool :D