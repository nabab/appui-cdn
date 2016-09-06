<?php
/** @var $ctrl \bbn\mvc\controller */

echo $ctrl->get_less() . $ctrl
  ->set_title('CDN Management')
  ->add_js()
  ->add_js('./libraries')
  ->add_js('./configurations', [
    'all_conf' => $ctrl->get_model('./configurations', ['db' => $ctrl->data['db']]),
    'all_lib' => $ctrl->get_model('./libraries', ['db' => $ctrl->data['db']]),
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
      'allert' => _("Set the folder name first, please."),
      'version' => _("Version"),
      'date' => _("Date"),
      'add_language' => ("Add language file"),
      'no_depend' => _("NO DEPENDENCIES"),
      'edit_library' => _("Edit library's version"),
      'select_dependece' => _("Select dependences..."),
      'add' => _("Add"),
      'add_version' => _("Add {0}'s new version"),
      'library_version' => _("library's version"),
      'version' => _("Version"),
      'add_theme_file' => _("Add theme file"),
      'new_library' => _("New Library"),
      'library' => _("Library"),
      'search_library' => _("Search library"),
      'editLib' => _("Edit Library"),
      'hash' => _("Hash"),
      'configuration' => _("Configuration"),
      //'version' => _("Version"),
      'cached' => _("cached"),
      'mod' => _("Mod."),
      'save' => _("Save"),
      'cancel' => _("Cancel"),
      'destroy' => _("Destroy"),
      'delete_this_entry'=> _("Are you sure that you want to delete this entry?"),
      'search_conf' => _("Search configuration"),
      'libraryVersion' => _("Library: {0} - Version: {1}"),
      'no_new_version' => _("You don't have new library's version to add!"),
      'order' => _("Order")
    ]
  ])
  ->get_view('', false);
