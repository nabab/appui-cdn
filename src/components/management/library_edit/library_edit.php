<bbn-form :action="management.action.post"
          :source="source.row"
          :data="complementaryData"
          ref="form_library"
          @failure="failure"
          @success="success"
          confirm-leave="<?=_("Are you sure you want to close?")?>"
          :buttons="currentButton"
          :scrollable="false"
          style="display: grid; grid-template-rows: auto max-content"

>
 <div class="bbn-padded bbn-grid-fields" v-if="!configuratorLibrary">
   <label><?=_("GitHub")?></label>
   <div class="bbn-flex-width">
     <bbn-input class="bbn-flex-fill" v-model="source.row.git"></bbn-input>
     <bbn-button @click="getInfo"
                 icon="nf nf-fa-download"
                 title="<?=_("Import library's info from GitHub")?>"
                 v-if="source.row.git"
     ></bbn-button>
   </div>

    <label class="bbn-r">
      <?=_("Title")?>
    </label>
    <bbn-input name="title" v-model="source.row.title"></bbn-input>

    <label class="bbn-r">
      <?=_("Folder name")?>
    </label>
    <bbn-input v-if="management.action.editLib" v-model="newName"></bbn-input>
    <bbn-input v-else v-model="source.row.name"></bbn-input>
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

  <!--SECOND STEP,VIEW FOR EDIT AND OR ADD VERSION -->
  <div class="secondStep bbn-padded" v-else>
    <bbn-splitter :scrollable="false"
                  orientation="vertical">
      <bbn-pane :size="350">
        <bbn-splitter orientation="horizontal">
          <bbn-pane>
            <div class="bbn-padded bbn-grid-fields bbn-100"
                 style="grid-auto-rows: max-content auto"
            >
              <span class="bbn-b">
                <?=_("Name:")?>
              </span>
              <bbn-input style="width: 100%" v-model="dataVersion.version" :disabled="disabledEditVersion"></bbn-input>
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
          <bbn-pane class="bbn-flex-height">
            <div class="bbn-padded">
              <div class="bbn-card bbn-c bbn-v-middle">
                <span class="bbn-b">
                  <?=_('Select files for order')?>
                </span>
              </div>
            </div>
            <div class="bbn-flex-fill bbn-h-100 bbn-flex-width" v-if ="treeOrderSource">
              <div class="bbn-padded">
                <div class="bbn-b bbn-c" style="padding-bottom: 5px">
                  <?=_("Move File")?>
                </div>
                <div class="bbn-card bbn-c" v-if="fileMove" style="margin-top: 15px">
                  <div class="bbn-padded">
                    <bbn-button icon="nf nf-fa-arrow_up" @click="moveUp"></bbn-button>
                  </div>
                  <div class="bbn-padded">
                    <bbn-button icon="nf nf-fa-arrow_down" @click="moveDown"></bbn-button>
                  </div>
                </div>
              </div>
              <div class="bbn-flex-fill">
                <bbn-scroll>
                  <template v-for="file in treeOrderSource">
                    <div v-text="file"
                         :style="{color: file === fileMove ? 'red' : 'inherit'}"
                         @click="selectFileMove(file)"
                         class="bbn-p bbn-b"
                    ></div>
                  </template>
                </bbn-scroll>
              </div>
            </div>
          </bbn-pane>
        </bbn-splitter>
      </bbn-pane>
      <!-- tables -->
      <bbn-pane>
        <bbn-splitter orientation="horizontal">
          <bbn-pane :size="160">
            <div class="bbn-padded bbn-w-100"  style="margin-bottom: 20px">
              <bbn-button :icon = "table === 'languages' ? 'nf nf-fa-eye_slash' : 'nf nf-fa-eye'"
                          @click = "showTable('languages')"
                          class="bbn-w-100"
                          :style = "{color: table === 'languages' ? 'red' : 'inherit'}"
              >
                <?=_('Languages')?>
              </bbn-button>
            </div>
            <div class="bbn-padded bbn-w-100" style="margin-bottom: 20px">
              <bbn-button :icon = "table === 'themes' ? 'nf nf-fa-eye_slash' : 'nf nf-fa-eye'"
                          @click = "showTable('themes')"
                          class="bbn-w-100"
                          :style = "{color: table === 'themes' ? 'red' : 'inherit'}"
              >
                <?=_('Themes')?>
              </bbn-button>
            </div>
            <div class="bbn-padded bbn-w-100 " style="margin-bottom: 20px">
              <bbn-button :icon = "table === 'dependencies' ? 'nf nf-fa-eye_slash' : 'nf nf-fa-eye'"
                          @click = "showTable('dependencies')"
                          class="bbn-w-100"
                          :style = "{color: table === 'dependencies' ? 'red' : 'inherit'}"
              >
                <?=_('Dependencies')?>
              </bbn-button>
            </div>
            <div class="bbn-padded bbn-w-100 " style="margin-bottom: 20px">
              <bbn-button :icon= "table === 'dependent' ? 'nf nf-fa-eye_slash' : 'nf nf-fa-eye'"
                          @click= "getDependent"
                          class= "bbn-w-100"
                          :style= "{color: table === 'dependent' ? 'red' : 'inherit'}"
                          v-if= "management.action.addVers === true"
              >
                <?=_('Dependent')?>
              </bbn-button>
            </div>
            <div class="bbn-padded">
              <bbn-checkbox v-model= "data.latest"
                            :disabled="abilitationCheckedLatest"
              ></bbn-checkbox>
              <span class="bbn-b">
                <?=_("Latest")?>
              </span>
            </div>
          </bbn-pane>
          <bbn-pane>
              <!--TABLE LANGUAGES-->
              <bbn-table :source="data.languages"
                         ref="tableLanguages"
                         v-if="table === 'languages'"
                         key="table_languages"
                         :scrollable="true"
              >
                <bbns-column title="<?=_('Languages Files')?>"
                              field="path"
                ></bbns-column>
                <bbns-column :tcomponent="$options.components.languages"
                              width="50"
                              :buttons="buttonDeleteLanguages"
                ></bbns-column>
              </bbn-table>
              <!--TABLE THEMES-->
              <bbn-table :source="data.themes"
                         ref="tableThemes"
                         v-if="table === 'themes'"
                         key="table_themes"
                         :scrollable="true"
                         :toolbar="$options.components.prepend_theme"
              >
                <bbns-column title="<?=_('Themes')?>"
                            field="path"
                ></bbns-column>
                <bbns-column  :tcomponent="$options.components.themes"
                              width="50"
                              :buttons="buttonDeleteThemes"
                ></bbns-column>
              </bbn-table>
              <!--TABLE Dependecies-->
              <bbn-table :source="dataVersion.dependencies"
                         ref="tableDependecies"
                         editable="inline"
                         :toolbar="[{
                           text: '<strong>'+'<?=_('Add dependencies')?>' + '</strong>',
                           icon: 'nf nf-fa-plus',
                           action: 'edit'
                         }]"                        
                        @saverow="saveDependencies"
                        v-if="table === 'dependencies'"
                        key="table_dependencies"
                        :scrollable="true"
              >
                <bbns-column title="<?=_('Library')?>"
                            field="lib_name"
                            :source="list"
                            :render="renderLibName"
                ></bbns-column>
                <bbns-column title="<?=_('Version')?>"
                            field="id_ver"
                            :editor="$options.components.versions"
                            :render="showVersion"
                ></bbns-column>
                <bbns-column title="<?=_('Order')?>"
                            field="order"
                            width="100"
                            type="number"
                ></bbns-column>
                <bbns-column title=" "
                            width="100"
                            cls="bbn-c"
                            :buttons="buttonsTableDepandencies"
                ></bbns-column>
              </bbn-table>
              <!--TABLE Dependent-->
              <bbn-table :source="dataVersion.slave_dependencies"
                         ref="tableDependent"
                         v-if="table === 'dependent' && management.action.addVers === true"
                         key="table_dependent"
                         :scrollable="true"
              >
                <bbns-column title="<?=_('Title')?>"
                             field="title"
                ></bbns-column>
                <bbns-column title="<?=_('Update')?>"
                             width="100"
                             :component="$options.components.update",
                             cls="bbn-c"
                ></bbns-column>
              </bbn-table>
          </bbn-pane>
        </bbn-splitter>
      </bbn-pane>
      <bbn-pane  v-if="dataVersion.dependencies_html.lenght"
                :scrollable="true"
      >
        <div class="bbn-c bbn-w-100" v-if="dataVersion.dependencies_html.lenght">
          <div class="bbn-w-50 bbn-card bbn-padded bbn-grid-fields">
            <div>
              <span class="bbn-b" v-text="_('Dependecies')"></span>
              <br>
              <span class="bbn-b" style="color:red" v-text="source.row.title"></span>
            </div>
            <div v-html="dataVersion.dependencies_html"></div>
          </div>
        </div>
      </bbn-pane>
    </bbn-splitter>
  </div>
</bbn-form>
