<?php
/** @var bbn\Mvc\Controller $ctrl */
$templates = \bbn\File\Dir::getFiles($ctrl->pluginPath('appui-cdn').'mvc/html/templates');
if (!empty($templates)) {
  $ctrl->data['templates'] = array_map(function($t)use($ctrl){
    return $ctrl->getView('./templates/'.basename($t, '.php'));
  }, $templates);
}
else{
  $ctrl->data['templates'] = [];
}

//in the case of an explicit refresh, we return only from this point, that is to say, in this case the bookshelves
if (isset($ctrl->post['refresh']) && !empty($ctrl->post['refresh'])) {
  $ctrl->obj = [
    'all_lib' => $ctrl->getModel('./data/libraries', ['db' => $ctrl->data['db']]),
  ];
}
else{
  $ctrl->combo('CDN Management', [
    'all_conf' => $ctrl->getModel('./configurations', ['db' => $ctrl->data['db']]),
    'all_lib' => $ctrl->getModel('./data/libraries', ['db' => $ctrl->data['db']]),
    'root' => APPUI_CDN_ROOT,
    'licences' => $ctrl->getModel('./licences'),
    'lng' => [
      'title' => _("Title"),
      'folderName' => _("Folder name"),
      'functionName' => _("Function name"),
      'latest' => _("Latest"),
      'author' => _("Author"),
      'licence' => _("Licence"),
      'SelectOne' => _("Select one"),
      'webSite' => _("Website"),
      'download' => _("Download"),
      'doc' => _("Documentation"),
      'gitHub' => "GitHub",
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
      'no_depend' => _("No dependencies"),
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
  ]);
}
