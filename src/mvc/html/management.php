<!-- CDN tabstrip -->
<div id="pe49ajAssj3knvVvn323" class="appui-h-100">
  <ul>
    <li class="k-state-active" style="width: 49%; text-align: center">
      <?=_("Libraries")?>
    </li>
    <li style="width: 49%; text-align: center">
      <?=_("Configurations")?>
    </li>
  </ul>
  <div class="appui-full-height" style="padding: 0; margin: 0; border: 0">
    <!-- All libraries grid -->
    <div id="RRsj983Jfjnv2kasihj234" class="appui-h-100"></div>
  </div>
  <div class="appui-full-height" style="padding: 0; margin: 0; border: 0">
    <!-- All configurations grid -->
    <div id="98324nas9t4pash9d2n3ifau" class="appui-h-100"></div>
  </div>
</div>


<!-- ## Templates ## -->

<!-- Libraries toolbar -->
<script type="text/html" id="kasdnjnsdfguiwerhasdh">
  <div class="toolbar">
    <!-- Search library -->
    <div style="float:left; display: inline-block">
      <i class="fa fa-search" style="margin: 0 5px"></i>
      <input id="F4LLL9jdn3nhasS38sh301dfs" style="width: 300px">
    </div>
    <!-- Add library button -->
    <div style="float:right; display: inline-block">
    <a class="k-button k-button-icontext k-grid-add" href="javascript:;">
      <i class="fa fa-plus" style="margin-right: 5px"></i><?=_("Add library")?>
    </a>
    </div>
  </div>
</script>

<!-- Library details popup -->
<script type="text/html" id="i3h34uefn94uh3rnfe9sfd23u">
  <!-- Info panel -->
  <div class="k-block">
    <div class="k-header k-shadow" style="text-align: center"><?=_("INFO")?></div>
    <div style="padding: 10px; height: 290px">
      <div class="appui-form-label"><strong><?=_("Title:")?></strong></div>
      <div class="appui-form-field">
        <div data-bind="text: title"></div>
      </div>
      <div class="appui-form-label"><strong><?=_("Folder name:")?></strong></div>
      <div class="appui-form-field">
        <div data-bind="text: name"></div>
      </div>
      <div class="appui-form-label"><strong><?=_("Function name:")?></strong></div>
      <div class="appui-form-field">
        <div data-bind="text: fname"></div>
      </div>
      <div class="appui-form-label"><strong><?=_("Latest version:")?></strong></div>
      <div class="appui-form-field">
        <div data-bind="text: latest"></div>
      </div>
      <div class="appui-form-label"><strong><?=_("Author:")?></strong></div>
      <div class="appui-form-field">
        <div data-bind="text: author"></div>
      </div>
      <div class="appui-form-label"><strong><?=_("Licence:")?></strong></div>
      <div class="appui-form-field">
        <div data-bind="text: licence"></div>
      </div>
      <div class="appui-form-label"><strong><?=_("WebSite:")?></strong></div>
      <div class="appui-form-field">
        <a data-bind="attr: { href: website }, text: website"></a>
      </div>
      <div class="appui-form-label"><strong><?=_("Download:")?></strong></div>
      <div class="appui-form-field">
        <a data-bind="attr: { href: download_link }, text: download_link"></a>
      </div>
      <div class="appui-form-label"><strong><?=_("Documentation:")?></strong></div>
      <div class="appui-form-field">
        <a data-bind="attr: { href: doc_link }, text: doc_link"></a>
      </div>
      <div class="appui-form-label"><strong><?=_("Support:")?></strong></div>
      <div class="appui-form-field">
        <a data-bind="attr: { href: support_link }, text: support_link"></a>
      </div>
      <div class="appui-form-label"><strong><?=_("GitHub:")?></strong></div>
      <div class="appui-form-field">
        <a data-bind="attr: { href: git }, text: git"></a>
      </div>
    </div>
  </div>
  <br>
  <!-- Library's versions panel -->
  <div class="k-block">
    <div class="k-header k-shadow" style="text-align: center; margin-bottom: 2px"><?=_("VERSIONS")?></div>
    <div>
      <div id="hufsa93hias9n38fn3293h389r2"></div>
    </div>
  </div>
</script>

<!-- Version details popup -->
<script type="text/html" id="kkk3jaSdh23490hqAsdha93">
  <div class="info-masonry-container">
  <!-- Info panel -->
    <div class="k-block info-masonry">
      <div class="k-header k-shadow" style="text-align: center"><?=_("INFO")?></div>
      <div style="padding: 10px">
        <strong><?=_("Version:")?> </strong>
        <span data-bind="text: name"></span>
        <strong style="margin-left: 20px"><?=_("Date:")?> </strong>
        <span data-bind="text: date_added"></span>
      </div>
    </div>
    <!-- Files panel -->
    <div class="k-block info-masonry">
      <div class="k-header k-shadow" style="text-align: center"><?=_("CONTENT")?></div>
      <!-- Files TreeView -->
      <div id="daij444jasdjhi332jiosdajo"></div>
    </div>
    <!-- Dependencies panel -->
    <div class="k-block info-masonry">
      <div class="k-header k-shadow" style="text-align: center"><?=_("DEPENDENCIES")?></div>
      <!-- Dependencies list -->
      <div id="isfih3huasdf92huf823hyhas93"></div>
    </div>
    <!-- Slave dependencies panel -->
    <div class="k-block info-masonry">
      <div class="k-header k-shadow" style="text-align: center"><?=_("SLAVE DEPENDENCIES")?></div>
      <!-- Dependencies list -->
      <div id="snjgdnasdoi234nasdnu1opteoh"></div>
    </div>
  </div>
</script>

<!-- Library's version add and edit -->
<script type="text/html" id="932f9u4923rjasdu09j3333">
  <div id="asdahf8923489yhf98923hr" style="display: none">
    <div class="appui-form-label appui-r fix-width no-padding"><?=_("Name")?></div>
    <div class="appui-form-field fix-width">
      <input id="u93248safn328dasuq89yu" class="k-textbox" name="vname" style="width: 100%" required>
    </div>
    <div class="appui-form-label appui-r fix-width no-padding"><?=_("Files")?></div>
    <div class="appui-form-field fix-width" style="display: flex">
      <div id="ashd3538y1i35h8oasdj023" style="display: inline-block; width: 49.7%"></div>
      <div style="display: inline-block; width: 49.7%">
        <div class="k-header k-shadow appui-c"><?=_("Files Order (drag&drop)")?></div>
        <div id="joisfd8723hifwe78238hds" style="padding: 5px"></div>
      </div>
    </div>
    <div class="appui-form-label appui-r fix-width no-padding"><?=_("Languages")?></div>
    <div class="appui-form-field fix-width">
      <div id="y7hhiawza3u9y983w2asj9h9xe4"></div>
    </div>
    <div class="appui-form-label appui-r fix-width no-padding"><?=_("Themes")?></div>
    <div class="appui-form-field fix-width">
      <div id="y99hu8y4ss3a2s5423ld453wmn"></div>
    </div>
    <div class="appui-form-label appui-r fix-width no-padding"><?=_("Latest")?></div>
    <div class="appui-form-field fix-width">
      <input id="hw4o5923noasd890324yho" type="checkbox" class="k-checkbox">
      <label for="hw4o5923noasd890324yho" class="k-checkbox-label"></label>
    </div>
    <div class="appui-form-label appui-r fix-width no-padding"><?=_("Dependecies")?></div>
    <div class="appui-form-field fix-width">
      <div id="732ijfasASdha92389yasdh9823"></div>
    </div>
  </div>
</script>

<!-- New language or theme file (library's version edit) -->
<script type="text/html" id="9342ja823hioasfy3oi">
  <div class="k-edit-label"><?=_("Path string")?></div>
  <div class="k-edit-field">
    <input class="k-textbox" name="path" style="width: 100%" required>
  </div>
  <div class="k-edit-label"><?=_("Files")?></div>
  <div class="k-edit-field">
    <div id="845hiay8h9fhuwiey823hi"></div>
  </div>
  <div class="k-edit-label"></div>
  <div class="k-edit-field" style="text-align: right">
    <a href="#" class="k-button k-button-icontext" style="margin-right: 5px">
      <span class="k-icon k-update"></span>
      <?=_("Save")?>
    </a>
    <a href="#" class="k-button k-button-icontext">
      <span class="k-icon k-cancel"></span>
      <?=_("Cancel")?>
    </a>
  </div>
</script>

<!-- Slave version's dependencies (add mode) -->
<script type="text/html" id="hfashio3289yasdnuqwy8232">
  <div class="appui-form-label appui-r fix-width no-padding"><?=_("Slave dependecies")
    ?></div>
  <div class="appui-form-field">
    <div class="k-block">
      <div class="k-header" style="margin-bottom: 0; font-size: inherit">
        <span style="margin-right: 10px; margin-left: 5px; cursor: pointer">
          <i class="fa fa-check-square-o ds-check"></i> <?=_("Check all")?>
        </span>
        <span style="cursor: pointer">
          <i class="fa fa-square-o ds-check"></i> <?=_("Uncheck all")?>
        </span>
      </div>
      <div style="padding: 5px">
        <form></form>
      </div>
    </div>
  </div>
</script>

<!-- ## END TEMPLATES ## -->