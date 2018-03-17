
<bbn-form class="bbn-full-screen"
          :action="management.action.post"
          :source="source.row"
          :data="complementaryData"
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
    <div>
      <bbn-dropdown style="width:200px"
                    ref="listLicences"
                    placeholder= "<?=_('Select one')?>"
                    :source="licencesList"
                    v-model="source.row.licence"
      ></bbn-dropdown>
      <bbn-input v-model="source.row.licence"></bbn-input>
    </div>

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
    <label v-if="source.row.versions"><?=_("Version")?></label>
    <bbn-dropdown v-if="source.row.versions"
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
            <div class="bbn-padded bbn-grid-fields bbn-100"
                 style="grid-auto-rows: max-content auto"
            >
              <span class="bbn-b">
                <?=_("Name:")?>
              </span>
              <bbn-input style="width: 100%" required v-model="dataVersion.version"></bbn-input>
              <span class="bbn-b">
                <?=_("Files:")?>
              </span>
              <bbn-tree :source="dataVersion.files_tree"
                        :checkable="true"
                        @check="checkFile"
                        @uncheck="uncheckFile"
                        :map="treeFiles"
                        uid="path"
                        ref="filesListTree"
                        @ready="checkedNode"
              ></bbn-tree>
            </div>
          </bbn-pane>
          <bbn-pane>
            <div class="bbn-padded">
              <div class="w3-card bbn-c bbn-v-middle">
                <span class="bbn-b">
                  <?=_('Select files for order')?>
                </span>
              </div>
            </div>
            <div class="bbn-padded bbn-grid-fields bbn-100" v-if ="treeOrderSource">
              <div>
                <span class="bbn-b" style="padding-bottom: 5px">
                  <?=_("Move File:")?>
                </span>
                <br>
                <div class="w3-card bbn-c" v-if="fileMove">
                  <div class="bbn-padded">
                    <bbn-button icon="fa fa-arrow-up" @click="moveUp"></bbn-button>
                  </div>
                  <div class="bbn-padded">
                    <bbn-button icon="fa fa-arrow-down" @click="moveDown"></bbn-button>
                  </div>
                </div>
              </div>
              <div>
                <template v-for="file in treeOrderSource">
                  <div v-text="file"
                       :style="{color: file === fileMove ? 'red' : 'inherit'}"
                       @click="selectFileMove(file)"
                       class="bbn-p bbn-b"
                  ></div>
                </template>
              </div>
            </div>

          </bbn-pane>
        </bbn-splitter>
      </bbn-pane>
      <bbn-pane>
        <bbn-splitter orientation="vertical">
          <bbn-pane :scrollable="true">

              <!--TABLE LANGUAGES-->
              <div style="height: 100px" class="bbn-w-100">
                <bbn-table :source="data.languages"
                           ref="tableLanguages"
                  >
                    <bbn-column title="<?=_('Languages Files')?>"
                                field="path"

                    ></bbn-column>
                    <bbn-column :tcomponent="$options.components.languages"
                                width="50"
                                :buttons="buttonDeleteLanguages"
                    ></bbn-column>
                </bbn-table>
              </div>
              <!--TABLE THEMES-->
              <div style="height: 100px" class="bbn-w-100">
                <bbn-table :source="data.themes"
                           ref="tableThemes"
                >
                  <bbn-column title="<?=_('Themes')?>"
                              field="path"

                  ></bbn-column>
                  <bbn-column :tcomponent="$options.components.themes"
                              width="50"
                              :buttons="buttonDeleteThemes"
                  ></bbn-column>
                </bbn-table>
              </div>
              <!--TABLE Dependecies-->
              <div style="height: 180px" class="bbn-w-100">
                <bbn-table  :source="dataVersion.dependencies"
                            ref="tableDependecies"
                            editable="inline"
                            :toolbar="[{
                              text: '<strong>'+'<?=_('Add dependencies')?>' + '</strong>',
                              icon: 'fa fa-plus',
                              command: 'edit'
                            }]"
                            @saveItem="saveDependencies"
                >
                  <bbn-column title="<?=_('Library')?>"
                              field="lib_name"
                              :source="listLib"
                  ></bbn-column>
                  <bbn-column title="<?=_('Version')?>"
                              field="id_ver"
                              :editor="$options.components.versions"
                              :render="showVersion"
                  ></bbn-column>
                  <bbn-column title="<?=_('Order')?>"
                              field="order"
                              width="100"
                              type="number"
                  ></bbn-column>
                  <bbn-column title=" "
                              width="100"
                              class="bbn-c"
                              :buttons="buttonsTableDepandencies"
                  ></bbn-column>

                </bbn-table>
              </div>

              <div class="bbn-c bbn-w-100" v-if="dataVersion.dependencies_html">
                <div class="bbn-w-50 w3-card bbn-padded bbn-grid-fields">
                  <div>
                    <span class="bbn-b" v-text="_('Dependecies')"></span>
                    <br>
                    <span class="bbn-b" style="color:red" v-text="source.row.title"></span>
                  </div>
                  <div v-html="dataVersion.dependencies_html"></div>
                </div>
              </div>

              <!--LATEST-->
              <div style="height: 180px" class="bbn-w-100 bbn-padded">

                  <span class="bbn-b">
                    <?=_("Latest")?>
                  </span>
                <bbn-checkbox v-model= "data.latest"
                              :novalue= "false"
                              :disabled="checkedLatest"
                ></bbn-checkbox>
                <!--INTERNAL-->
                <bbn-dropdown v-if="!data.latest"
                              :source="sourceInternal"
                              v-model="data.internal"
                ></bbn-dropdown>
              </div>
          </bbn-pane>
        </bbn-splitter>
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
