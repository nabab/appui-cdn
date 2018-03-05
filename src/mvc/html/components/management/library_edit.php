
<bbn-form class="bbn-full-screen"
          :action="currentAction"
          :source="source.row"
          ref="form_library"
          @failure="failure"
          @success="success"
          confirm-leave="<?=_("Are you sure you want to go out?")?>"
          :buttons="currentButton"
>
 <div class="bbn-padded bbn-grid-fields" v-if="!configuratorLibrary">
    <label class="bbn-r">
      <?=_("Title")?>
    </label>
    <bbn-input name="title" v-model="source.row.title"></bbn-input>

    <label class="bbn-r">
      <?=_("Folder name")?>
    </label>
    <bbn-input v-model="source.row.name"></bbn-input>

    <label class="bbn-r">
      <?=_("Function name")?>
    </label>
    <bbn-input v-model="source.row.fname"></bbn-input>

    <label class="bbn-r">
      <?=_("Description")?>
    </label>
    <bbn-input v-model="source.row.description"></bbn-input>

    <label class="bbn-r">
      <?=_("Author")?>
    </label>
    <bbn-input v-model="source.row.author"></bbn-input>

    <label class="bbn-r">
      <?=_("Licence")?>
    </label>
    <bbn-dropdown style="width:200px"
                  ref="listLicences"
                  placeholder= "<?=_('Select one')?>"
                  :source="licencesList"
                  v-model="source.row.licence"
    ></bbn-dropdown>

    <label>
      <?=_("Web site")?>
    </label>
    <bbn-input v-model="source.row.website"></bbn-input>

    <label class="bbn-r">
      <?=_("Download")?>
    </label>
    <bbn-input v-model="source.row.download_link"></bbn-input>

    <label class="bbn-r">
      <?=_("Documentation")?>
    </label>
    <bbn-input v-model="source.row.doc_link"></bbn-input>

    <label><?=_("GitHub")?></label>
    <div class="bbn-flex-width">
      <bbn-input class="bbn-flex-fill" v-model="source.row.git"></bbn-input>
      <bbn-button @click="getInfo"
                  icon="fa fa-download"
                  title="<?=_("Import library's info from GitHub")?>"
                  v-if="source.row.git"
      ></bbn-button>
    </div>

    <label class="bbn-r">
      <?=_("Support")?>
    </label>
    <bbn-input v-model="source.row.support_link"></bbn-input>

    <label v-if="source.row.versions.length"><?=_("Version")?></label>
    <bbn-dropdown v-if="source.row.versions.length"
                  style="width: 50%"
                  v-model="source.row.git_id"
                  :source="source.row.versions"
                  ref="listVersions"
    ></bbn-dropdown>
  </div>
   <!--932f9u4923rjasdu09j3333-->
  <div v-else>
    <bbn-splitter orientation="vertical">
      <bbn-pane>
        <bbn-splitter orientation="horizontal">
          <bbn-pane>
            <div class="bbn-padded bbn-full-screen">
              <span>
                <strong>
                  <?=_("Name:")?>
                </strong>
              </span>
              <bbn-input style="width: 100%" required v-model="dataVersion.version"></bbn-input>
              <span>
                <strong>
                  <?=_("Files:")?>
                </strong>
              </span>
              <bbn-tree :source="dataVersion.files_tree"
                        :checkable="true"
                        :map="treeFiles"
                        uid="id"
                        ref="filesListTree"
              ></bbn-tree>
            </div>
          </bbn-pane>
          <bbn-pane>
            <div class="bbn-padded">
              <div class="bbn-padded bbn-full-screen">
                <div class="bbn-block bbn-h-50 bbn-w-20">
                  <strong>
                    <?=_("Files Order (drag&drop)")?>
                  </strong>
                </div>
                <div class="bbn-block bbn-h-100 bbn-w-80">
                  <div class="bbn-full-screen">
                    <div class="bbn-100">
                      <!--<bbn-tree :source="treeFiles"
                                :draggable="true"
                                @dragEnd="orderFiles"
                                @select="selectElement"
                                ref="orderFilesTree"
                      ></bbn-tree>-->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </bbn-pane>
        </bbn-splitter>
      </bbn-pane>
      <bbn-pane>
          <div class="bbn-padded bbn-full-screen">
            <div>
              <span>
                <strong>
                  <?=_("Languages")?>
                </strong>
              </span>
            </div>
            <bbn-table :source="data.languages">
              <bbn-column title="<?=_('Files:')?>"
                          field="path"
              ></bbn-column>
              <bbn-column :tcomponent="$options.components['appui-cdn-management-btn-language-add-file']"
                          width="50"
                          :buttons="buttonLanguageCols"
              ></bbn-column>
            </bbn-table>
          </div>
      </bbn-pane>
    </bbn-splitter>


   <!--
   <script type="text/html" id="932f9u4923rjasdu09j3333">
     <div id="asdahf8923489yhf98923hr" style="display: none">
       <div class="bbn-form-label bbn-r fix-width no-padding"><?=_("Name")?></div>
       <div class="bbn-form-field fix-width">
         <input id="u93248safn328dasuq89yu" class="k-textbox" name="vname" style="width: 100%" required data-bind="value: version">
       </div>
       <div class="bbn-form-label bbn-r fix-width no-padding"><?=_("Files")?></div>
       <div class="bbn-form-field fix-width" style="display: flex">
         <div class="bbn-w-50">
           <div id="ashd3538y1i35h8oasdj023"></div>
         </div>
         <div class="bbn-w-50">
           <div class="k-header k-shadow bbn-c"><?=_("Files Order (drag&drop)")?></div>
           <div id="joisfd8723hifwe78238hds" style="padding: 5px"></div>
         </div>
       </div>
       <div class="bbn-form-label bbn-r fix-width no-padding"><?=_("Languages")?></div>
       <div class="bbn-form-field fix-width">
         <div id="y7hhiawza3u9y983w2asj9h9xe4"></div>
       </div>
       <div class="bbn-form-label bbn-r fix-width no-padding"><?=_("Themes")?></div>
       <div class="bbn-form-field fix-width">
         <div id="y99hu8y4ss3a2s5423ld453wmn"></div>
       </div>
       <div class="bbn-form-label bbn-r fix-width no-padding"><?=_("Latest")?></div>
       <div class="bbn-form-field fix-width">
         <input id="hw4o5923noasd890324yho" type="checkbox" class="k-checkbox">
         <label for="hw4o5923noasd890324yho" class="k-checkbox-label"></label>
       </div>
       <div class="bbn-form-label bbn-r fix-width no-padding"><?=_("Dependecies")?></div>
       <div class="bbn-form-field fix-width">
         <div id="732ijfasASdha92389yasdh9823"></div>
       </div>
       <div class="bbn-form-label bbn-r fix-width no-padding" data-bind="visible: dependencies_html">
         <?=_("To add these dependecies")?>
       </div>
       <div class="bbn-form-field fix-width" data-bind="visible: dependencies_html">
         <div id="iashoiw58y2lkas8234ljka823" data-bind="html: dependencies_html"></div>
       </div>
     </div>
   </script>
-->

 </div>
</bbn-form>
