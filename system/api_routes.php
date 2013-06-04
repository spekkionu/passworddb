<?php
Slim_Route::setDefaultConditions(array(
  'id' => '\d+',
  'website_id' => '\d+'
));

//////////////////////////
// General API Methods  //
//////////////////////////
$app->get('/api/', 'Api_Controller_Index::listAction');

$app->notFound('Api_Controller_Error::notfoundAction');

//////////////////////
// Website Methods  //
//////////////////////
// Count Websites
$app->get('/api/website/count', 'Api_Controller_Website::countAction');

// List Websites
$app->get('/api/website', 'Api_Controller_Website::listAction');

// Get Website
$app->get('/api/website/:id', 'Api_Controller_Website::detailsAction');

// Create a new website
$app->post('/api/website', 'Api_Controller_Website::addAction');

// Update an individual website
$app->put('/api/website/:id', 'Api_Controller_Website::updateAction');

// Delete an individual website
$app->delete('/api/website/:id', 'Api_Controller_Website::deleteAction');

//////////////////
// FTP Methods  //
//////////////////
// List FTP logins
$app->get('/api/ftp/:website_id', 'Api_Controller_FTP::listAction');

// Get FTP login details
$app->get('/api/ftp/:website_id/:id', 'Api_Controller_FTP::detailsAction');

// Add new FTP login
$app->post('/api/ftp/:website_id', 'Api_Controller_FTP::addAction');

// Update existing FTP login
$app->put('/api/ftp/:website_id/:id', 'Api_Controller_FTP::updateAction');

// Delete existing FTP login
$app->delete('/api/ftp/:website_id/:id', 'Api_Controller_FTP::deleteAction');

//////////////////////////
// Admin Login Methods  //
//////////////////////////
// List Admin logins
$app->get('/api/admin/:website_id', 'Api_Controller_Admin::listAction');

// Get existing admin login
$app->get('/api/admin/:website_id/:id', 'Api_Controller_Admin::detailsAction');

// Add new admin login
$app->post('/api/admin/:website_id', 'Api_Controller_Admin::addAction');

// Update existing admin login
$app->put('/api/admin/:website_id/:id', 'Api_Controller_Admin::updateAction');

// Delete existing admin login
$app->delete('/api/admin/:website_id/:id', 'Api_Controller_Admin::deleteAction');

////////////////////////////
// Control Panel Methods  //
////////////////////////////
// List control panel logins
$app->get('/api/controlpanel/:website_id', 'Api_Controller_ControlPanel::listAction');

// Get control panel login
$app->get('/api/controlpanel/:website_id/:id', 'Api_Controller_ControlPanel::detailsAction');

// Add new control panel login
$app->post('/api/controlpanel/:website_id', 'Api_Controller_ControlPanel::addAction');

// Update existing control panel login
$app->put('/api/controlpanel/:website_id/:id', 'Api_Controller_ControlPanel::updateAction');

// Delete existing control panel login
$app->delete('/api/controlpanel/:website_id/:id', 'Api_Controller_ControlPanel::deleteAction');

///////////////////////
// Database Methods  //
///////////////////////
// List database credentials
$app->get('/api/database/:website_id', 'Api_Controller_Database::listAction');

// Get database credentials
$app->get('/api/database/:website_id/:id', 'Api_Controller_Database::detailsAction');

// Add new database credentials
$app->post('/api/database/:website_id', 'Api_Controller_Database::addAction');

// Update existing database credentials
$app->put('/api/database/:website_id/:id', 'Api_Controller_Database::updateAction');

// Delete existing database credentials
$app->delete('/api/database/:website_id/:id', 'Api_Controller_Database::deleteAction');

