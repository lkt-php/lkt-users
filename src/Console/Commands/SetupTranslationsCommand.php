<?php

namespace Lkt\Users\Console\Commands;

use Lkt\Translations\Enums\TranslationType;
use Lkt\Translations\Instances\LktTranslation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetupTranslationsCommand extends Command
{
    protected static $defaultName = 'lkt:user:setup:i18n';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Automatically generates all default translations')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $parent = LktTranslation::createIfMissing('userRoleCapability', TranslationType::Many, []);
        $parentId = $parent->getId();
        LktTranslation::createIfMissing('0', TranslationType::Text, [
            'es' => 'Deshabilitado',
            'en' => 'Disabled',
        ], $parentId);
        LktTranslation::createIfMissing('1', TranslationType::Text, [
            'es' => 'Elementos propios',
            'en' => 'Owned items',
        ], $parentId);
        LktTranslation::createIfMissing('2', TranslationType::Text, [
            'es' => 'Todos los elementos',
            'en' => 'All items',
        ], $parentId);


        $parent = LktTranslation::createIfMissing('userThemeModes', TranslationType::Many, []);
        $parentId = $parent->getId();
        LktTranslation::createIfMissing('0', TranslationType::Text, [
            'es' => 'Por defecto del sistema',
            'en' => 'System default',
        ], $parentId);
        LktTranslation::createIfMissing('1', TranslationType::Text, [
            'es' => 'Claro',
            'en' => 'Light',
        ], $parentId);
        LktTranslation::createIfMissing('2', TranslationType::Text, [
            'es' => 'Oscuro',
            'en' => 'Dark',
        ], $parentId);

        $parent = LktTranslation::createIfMissing('userForm', TranslationType::Many, []);
        $parentId = $parent->getId();
        LktTranslation::createIfMissing('firstName', TranslationType::Text, [
            'es' => 'Nombre',
            'en' => 'Name',
        ], $parentId);
        LktTranslation::createIfMissing('lastName', TranslationType::Text, [
            'es' => 'Apellidos',
            'en' => 'Lastname',
        ], $parentId);
        LktTranslation::createIfMissing('email', TranslationType::Text, [
            'es' => 'Email',
            'en' => 'Email',
        ], $parentId);
        LktTranslation::createIfMissing('addUser', TranslationType::Text, [
            'es' => 'Agregar usuario',
            'en' => 'Add user',
        ], $parentId);
        LktTranslation::createIfMissing('addUserAndNew', TranslationType::Text, [
            'es' => 'Agregar usuario y seguir',
            'en' => 'Add user and new',
        ], $parentId);
        LktTranslation::createIfMissing('titleSingle', TranslationType::Text, [
            'es' => 'Usuario',
            'en' => 'User',
        ], $parentId);
        LktTranslation::createIfMissing('titleMany', TranslationType::Text, [
            'es' => 'Usuarios',
            'en' => 'Users',
        ], $parentId);

        LktTranslation::createIfMissing('canReceiveMailNotifications', TranslationType::Text, [
            'es' => 'Recibir notificaciones por correo',
            'en' => 'Send mail notifications',
        ], $parentId);

        LktTranslation::createIfMissing('canReceivePushNotifications', TranslationType::Text, [
            'es' => 'Recibir notificaciones push',
            'en' => 'Send push notifications',
        ], $parentId);

        $parent = LktTranslation::createIfMissing('canReceiveMailNotificationsOptions', TranslationType::Many, []);
        $parentId = $parent->getId();
        LktTranslation::createIfMissing('0', TranslationType::Text, [
            'es' => 'Solo esenciales',
            'en' => 'Only essentials',
        ], $parentId);
        LktTranslation::createIfMissing('1', TranslationType::Text, [
            'es' => 'Todas',
            'en' => 'All',
        ], $parentId);

        $parent = LktTranslation::createIfMissing('canReceivePushNotificationsOptions', TranslationType::Many, []);
        $parentId = $parent->getId();
        LktTranslation::createIfMissing('0', TranslationType::Text, [
            'es' => 'Solo esenciales',
            'en' => 'Only essentials',
        ], $parentId);
        LktTranslation::createIfMissing('1', TranslationType::Text, [
            'es' => 'Todas',
            'en' => 'All',
        ], $parentId);


        $parent = LktTranslation::createIfMissing('userRoleForm', TranslationType::Many, []);
        $parentId = $parent->getId();
        LktTranslation::createIfMissing('name', TranslationType::Text, [
            'es' => 'Nombre',
            'en' => 'Name',
        ], $parentId);
        LktTranslation::createIfMissing('permissions', TranslationType::Text, [
            'es' => 'Permisos',
            'en' => 'Permissions',
        ], $parentId);
        LktTranslation::createIfMissing('component', TranslationType::Text, [
            'es' => 'Componente',
            'en' => 'Component',
        ], $parentId);
        LktTranslation::createIfMissing('ls', TranslationType::Text, [
            'es' => 'Listar elementos',
            'en' => 'List items',
        ], $parentId);
        LktTranslation::createIfMissing('mk', TranslationType::Text, [
            'es' => 'Crear elementos',
            'en' => 'Create items',
        ], $parentId);
        LktTranslation::createIfMissing('r', TranslationType::Text, [
            'es' => 'Leer elemento',
            'en' => 'Read item',
        ], $parentId);
        LktTranslation::createIfMissing('up', TranslationType::Text, [
            'es' => 'Actualizar elementos',
            'en' => 'Update items',
        ], $parentId);
        LktTranslation::createIfMissing('rm', TranslationType::Text, [
            'es' => 'Eliminar elementos',
            'en' => 'Drop items',
        ], $parentId);
        LktTranslation::createIfMissing('add', TranslationType::Text, [
            'es' => 'Agregar rol',
            'en' => 'Add role',
        ], $parentId);
        LktTranslation::createIfMissing('addAndNew', TranslationType::Text, [
            'es' => 'Agregar rol y seguir',
            'en' => 'Add role and new',
        ], $parentId);
        LktTranslation::createIfMissing('titleSingle', TranslationType::Text, [
            'es' => 'Rol de usuario',
            'en' => 'User role',
        ], $parentId);
        LktTranslation::createIfMissing('titleMany', TranslationType::Text, [
            'es' => 'Roles de usuario',
            'en' => 'User Roles',
        ], $parentId);

        return 1;
    }
}