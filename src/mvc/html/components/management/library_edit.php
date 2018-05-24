<bbn-form class="bbn-full-screen"
          :action="management.action.post"
          :source="source.row"
          :data="complementaryData"
          ref="form_library"
          @failure="failure"
          @success="success"
          confirm-leave="<?=_("Are you sure you want to close?")?>"
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
  <!--SECOND STEP,VIEW FOR EDIT AND OR ADD VERSION -->
  <div v-else>
    <bbn-splitter orientation="vertical">
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
          <bbn-pane>
            <div class="bbn-padded">
              <div class="w3-card bbn-c bbn-v-middle">
                <span class="bbn-b">
                  <?=_('Select files for order')?>
                </span>
              </div>
            </div>
            <div class="bbn-padded bbn-100 bbn-flex-width" v-if ="treeOrderSource">
              <div class="bbn-h-100 bbn-padded">
                <div class="bbn-b bbn-c" style="padding-bottom: 5px">
                  <?=_("Move File")?>
                </div>

                <div class="w3-card bbn-c" v-if="fileMove" style="margin-top: 15px">
                  <div class="bbn-padded">
                    <bbn-button icon="fa fa-arrow-up" @click="moveUp"></bbn-button>
                  </div>
                  <div class="bbn-padded">
                    <bbn-button icon="fa fa-arrow-down" @click="moveDown"></bbn-button>
                  </div>
                </div>
              </div>
              <div class="bbn-h-100 bbn-flex-fill">
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
            <div class="bbn-padded">
              <bbn-button v-if = "!tableShow.languages"
                          icon = "fa fa-eye"
                          @click = "()=>{tableShow.languages= true}"
              ></bbn-button>
              <bbn-button v-else
                          icon = "fa fa-eye-slash"
                          @click = "()=>{tableShow.languages= false}"
              ></bbn-button>
              <span class="bbn-b"
                    v-text = "_('Languages')"
                    :style = "{color: tableShow.languages ? 'red' : 'inherit'}"
              >
              </span>
            </div>
            <div class="bbn-padded">
              <bbn-button v-if = "!tableShow.themes"
                          icon = "fa fa-eye"
                          @click ="()=>{tableShow.themes= true}"
              ></bbn-button>
              <bbn-button v-else
                          icon = "fa fa-eye-slash"
                          @click = "()=>{tableShow.themes= false}"
              ></bbn-button>
              <span class = "bbn-b"
                    v-text = "_('Themes')"
                    :style = "{color: tableShow.themes ? 'red' : 'inherit'}"
              >
              </span>
            </div>
            <div class="bbn-padded">
              <bbn-button v-if = "!tableShow.dependencies"
                          icon = "fa fa-eye"
                          @click = "()=>{tableShow.dependencies= true}"
              ></bbn-button>
              <bbn-button v-else
                          icon = "fa fa-eye-slash"
                          @click = "()=>{tableShow.dependencies= false}"
              ></bbn-button>
              <span class = "bbn-b"
                    v-text = "_('Dependencies')"
                    :style = "{color: tableShow.dependencies ? 'red' : 'inherit'}">
              </span>
            </div>
            <div class="bbn-w-100 bbn-padded">
              <bbn-checkbox v-model= "data.latest"
                            :disabled="abilitationCheckedLatest"
              ></bbn-checkbox>
              <span class="bbn-b">
                <?=_("Latest")?>
              </span>
            </div>
          </bbn-pane>
          <bbn-pane :scrollable="true">
            <!--TABLE LANGUAGES-->
            <div class="bbn-w-100" style="height: 220px" v-if="tableShow.languages">
              <bbn-table v-if="tableShow.languages"
                         :source="data.languages"
                         ref="tableLanguages"
                         class="bbn-full-screen"
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
            <div class="bbn-w-100" style="height: 220px" v-if="tableShow.themes">
              <bbn-table v-if="tableShow.themes"
                         :source="data.themes"
                         ref="tableThemes"
                         class="bbn-full-screen"
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
            <div class="bbn-w-100" style="height: 220px" v-if="tableShow.dependencies">
              <bbn-table v-if="tableShow.dependencies"
                         :source="dataVersion.dependencies"
                         ref="tableDependecies"
                         editable="inline"
                         class="bbn-full-screen"
                         :toolbar="[{
                           text: '<strong>'+'<?=_('Add dependencies')?>' + '</strong>',
                           icon: 'fa fa-plus',
                           command: 'edit'
                         }]"
                        @saveItem="saveDependencies"
              >
                <bbn-column title="<?=_('Library')?>"
                            field="lib_name"
                            :source="list"
                            :render="renderLibName"
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
          </bbn-pane>
        </bbn-splitter>
      </bbn-pane>
      <bbn-pane  v-if="dataVersion.dependencies_html.lenght"
                :scrollable="true"
      >
        <div class="bbn-c bbn-w-100" v-if="dataVersion.dependencies_html.lenght">
          <div class="bbn-w-50 w3-card bbn-padded bbn-grid-fields">
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
