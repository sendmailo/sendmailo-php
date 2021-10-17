<?php

declare(strict_types=1);

/*
 * Copyright (C) 2021 Sendmailo
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Sendmailo;

/**
 * PHP version 7.2.
 *
 * This is the Sendmailo Endpoints Class
 *
 * @category Sendmailo_API
 *
 * @author Guillaume Badi <gbadi@sendmailo.com>
 * @license MIT https://opensource.org/licenses/MIT
 *
 * @see dev.sendmailo.com
 */
class Endpoints
{
    public static $Email = ['send', ''];
    public static $Aggregategraphstatistics = ['aggregategraphstatistics', ''];
    public static $Apikey = ['apikey', ''];
    public static $Apikeyaccess = ['apikeyaccess', ''];
    public static $Apikeytotals = ['apikeytotals', ''];
    public static $Contact = ['contact', ''];
    public static $ContactManagecontactslists = ['contact', 'managecontactslists'];
    public static $ContactGetcontactslists = ['contact', 'getcontactslists'];
    public static $ContactManagemanycontacts = ['contact', 'managemanycontacts'];
    public static $Contactdata = ['contactdata', ''];
    public static $Contactfilter = ['contactfilter', ''];
    public static $Contacthistorydata = ['contacthistorydata', ''];
    public static $Contactmetadata = ['contactmetadata', ''];
    public static $Contactslist = ['contactslist', ''];
    public static $ContactslistCsvdata = ['contactslist', 'csvdata/text:plain'];
    public static $ContactslistManagecontact = ['contactslist', 'ManageContact'];
    public static $ContactslistManagemanycontacts = ['contactslist', 'ManageManyContacts'];
    public static $ContactslistImportlist = ['contactslist', 'ImportList'];
    public static $Contactslistsignup = ['contactslistsignup', ''];
    public static $Contactstatistics = ['contactstatistics', ''];
 
    public static $Template = ['template', ''];
    public static $TemplateDetailcontent = ['template', 'detailcontent'];
    public static $TemplateDetailpreviews = ['template', 'detailpreviews'];
    public static $TemplateDisplaypreview = ['template', 'displaypreview'];
    public static $TemplateDetailthumbnail = ['template', 'detailthumbnail'];
    public static $TemplateDisplaythumbnail = ['template', 'displaythumbnail'];

}
