<?php
/** @var $ctrl \bbn\mvc\controller */

$templates = \bbn\file\dir::get_files($ctrl->plugin_path('bbn-cdn').'mvc/html/templates');
if ( !empty($templates) ){
  $templates = array_map(function($t){
    return './templates/'.basename($t, '.php');
  }, $templates);
}
else{
  $templates = [];
}

echo $ctrl->get_less() . $ctrl
  ->set_title('CDN Management')
  ->add_js_group([
    './libraries',
    './versions',
    './library/add',
    './library/edit',
    './functions/get_checked',
    './functions/files_order',
    './version/add',
    './version/edit',
    './version/info',
    './version/from_github',
    './configurations',
    ''
  ], [
    'all_conf' => $ctrl->get_model('./configurations', ['db' => $ctrl->data['db']]),
    'all_lib' => $ctrl->get_model('./data/libraries', ['db' => $ctrl->data['db']]),
    'licences' => $ctrl->get_model('./licences'),
    'lng' => [
      'title' => _("Title"),
      'folderName' => _("Folder name"),
      'functionName' => _("Function name"),
      'latest' => _("Latest"),
      'author' => _("Author"),
      'licence' => _("Licence"),
      'SelectOne' => _("Select one"),
      'webSite' => _("Web site"),
      'download' => _("Download"),
      'doc' => _("Documentation"),
      'gitHub' => _("GitHub"),
      'supp' => _("Support"),
      'info' => _("info"),
      'edit' => _("edit"),
      'new_libr_vers' => _("New Library's Version"),
      'mod' => _("Mod."),
      'save' => _("Save"),
      'cancel' => _("Cancel"),
      'destroy' => _("Destroy"),
      'del' => _("Del"),
      'delete_this_entry'=> _("Are you sure that you want to delete this entry?"),
      'next' => _("Next"),
      'files' => _("Files"),
      'name' => _("Name"),
      'before' => _("Before"),
      'setFolderName' => _("Set the folder name first, please."),
      'version' => _("Version"),
      'date' => _("Date"),
      'add_language' => ("Add language file"),
      'no_depend' => _("NO DEPENDENCIES"),
      'edit_library' => _("Edit library's version"),
      'select_dependece' => _("Select dependences..."),
      'add' => _("Add"),
      'add_version' => _("Add new version: {0}"),
      'library_version' => _("library's version"),
      'add_theme_file' => _("Add theme file"),
      'new_library' => _("New Library"),
      'library' => _("Library"),
      'search_library' => _("Search library"),
      'editLib' => _("Edit Library"),
      'hash' => _("Hash"),
      'configuration' => _("Configuration"),
      'cached' => _("cached"),
      'search_conf' => _("Search configuration"),
      'libraryVersion' => _("Library: {0} - Version: {1}"),
      'noNewVersion' => _("You don't have a new library's version to add."),
      'versionGithubImport' => _("You don't have a new library's version to add (locally). Do you want to import it from GitHub?"),
      'order' => _("Order"),
      'skip' => _("Skip"),
      'import' => _("Import"),
      'githubVersion' => _("Select the version you want to import from GitHub"),
      'githubUpdates' => _("GitHub updates"),
      'updates' => _("Updates"),
      'checkUpdates' => _("Do you want to check updates from GitHub? This may take a long time.")
    ]
  ])
  ->get_view_group(\bbn\x::merge_arrays($templates, [
    ''
  ]));
