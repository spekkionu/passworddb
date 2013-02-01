<?php
Slim_Route::setDefaultConditions(array(
  'id' => '\d+',
  'website_id' => '\d+',
  'page' => '\d+'
));

########################
# General API Methods  #
########################

$app->notFound('Controller_Error::notfoundAction');

$app->error('Controller_Error::errorAction');

####################
# Website Methods  #
####################
$app->get('/(:page)', 'Controller_Website::listAction')->name('home');

// Get Website
$app->get('/website/:id', 'Controller_Website::detailsAction')->name('website');

// Create a new website
$app->map('/website/add', 'Controller_Website::addAction')->via('GET', 'POST')->name('website_add');

// Update an individual website
$app->map('/website/:id/edit', 'Controller_Website::updateAction')->via('GET', 'POST', 'PUT')->name('website_edit');

// Delete an individual website
$app->map('/website/:id/delete', 'Controller_Website::deleteAction')->via('GET', 'POST', 'DELETE')->name('website_delete');

################
# FTP Methods  #
################
// Add new FTP login
$app->map('/ftp/:website_id/add', 'Controller_FTP::addAction')->via('GET', 'POST')->name('ftp_add');

// Update existing FTP login
$app->map('/ftp/:website_id/:id/edit', 'Controller_FTP::updateAction')->via('GET', 'POST', 'PUT')->name('ftp_edit');

// Delete existing FTP login
$app->map('/ftp/:website_id/:id/delete', 'Controller_FTP::deleteAction')->via('GET', 'POST', 'DELETE')->name('ftp_delete');

########################
# Admin Login Methods  #
########################
// Add new admin login
$app->map('/admin/:website_id/add', 'Controller_Admin::addAction')->via('GET', 'POST')->name('admin_add');

// Update existing admin login
$app->map('/admin/:website_id/:id/edit', 'Controller_Admin::updateAction')->via('GET', 'POST', 'PUT')->name('admin_edit');

// Delete existing admin login
$app->map('/admin/:website_id/:id/delete', 'Controller_Admin::deleteAction')->via('GET', 'POST', 'DELETE')->name('admin_delete');

##########################
# Control Panel Methods  #
##########################
// Add new control panel login
$app->map('/controlpanel/:website_id/add', 'Controller_ControlPanel::addAction')->via('GET', 'POST')->name('controlpanel_add');

// Update existing control panel login
$app->map('/controlpanel/:website_id/:id/edit', 'Controller_ControlPanel::updateAction')->via('GET', 'POST', 'PUT')->name('controlpanel_edit');

// Delete existing control panel login
$app->map('/controlpanel/:website_id/:id/delete', 'Controller_ControlPanel::deleteAction')->via('GET', 'POST', 'DELETE')->name('controlpanel_delete');

#####################
# Database Methods  #
#####################
// Add new database credentials
$app->map('/database/:website_id/add', 'Controller_Database::addAction')->via('GET', 'POST')->name('database_add');

// Update existing database credentials
$app->map('/database/:website_id/:id/edit', 'Controller_Database::updateAction')->via('GET', 'POST', 'PUT')->name('database_edit');

// Delete existing database credentials
$app->map('/database/:website_id/:id/delete', 'Controller_Database::deleteAction')->via('GET', 'POST', 'DELETE')->name('database_delete');