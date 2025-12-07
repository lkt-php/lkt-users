<?php

namespace Lkt\Users\Config\Schemas;

use Lkt\Factory\Schemas\Fields\DateTimeField;
use Lkt\Factory\Schemas\Fields\EmailField;
use Lkt\Factory\Schemas\Fields\ForeignKeysField;
use Lkt\Factory\Schemas\Fields\IdField;
use Lkt\Factory\Schemas\Fields\StringField;
use Lkt\Factory\Schemas\InstanceSettings;
use Lkt\Factory\Schemas\Schema;
use Lkt\Users\Instances\LktUser;
use Lkt\Users\Instances\LktUserRole;

Schema::add(
    Schema::table('lkt_users', LktUser::COMPONENT)
        ->setInstanceSettings(
            InstanceSettings::define(LktUser::class)
                ->setNamespaceForGeneratedClass('Lkt\Users\Generated')
                ->setWhereStoreGeneratedClass(__DIR__ . '/../../Generated')
        )
        ->setItemsPerPage(20)
        ->setCountableField('id')
        ->addField(IdField::define('id'))
        ->addField(
            DateTimeField::define('createdAt', 'created_at')
                ->setDefaultReadFormat('Y-m-d')
                ->setCurrentTimeStampAsDefaultValue()
        )
        ->addField(
            DateTimeField::define('updatedAt', 'updated_at')
                ->setDefaultReadFormat('Y-m-d')
                ->setCurrentTimeStampAsDefaultValue()
        )
        ->addField(StringField::define('firstName', 'firstname'))
        ->addField(StringField::define('lastName', 'lastname'))
        ->addField(EmailField::define('email'))
        ->addField(StringField::define('password'))
        ->addField(StringField::define('credentialIdentifier', 'credential_id'))
        ->addField(ForeignKeysField::defineRelation(LktUserRole::COMPONENT, 'appRoles', 'app_roles'))
        ->addField(ForeignKeysField::defineRelation(LktUserRole::COMPONENT, 'adminRoles', 'admin_roles'))
        ->addField(StringField::define('sessionToken', 'session_token'))
);