(() => {
  var editLib;
  return {
    data() {
      return {
        management: this.closest("bbn-container").getComponent(),
        showForm: false,
        licencesList: [],
        checkedFile: [],
        referenceNodeTree: [],
        copyItemsTree: [],
        currentButton: false,
        dataVersion: {
          dependencies: [],
          dependencies_html: "",
          files_tree: [],
          internal: [],
          languages_tree: [],
          themes_tree: [],
          lib_ver: [],
          slave_dependencies: [],
          version: ""
        },
        listLib: [],
        //for edit
        newName: "",
        //for tables languages file and theme of library
        data: {
          languages: [],
          themes: [],
          latest: true,
          internal: 0,
          versions: []
        },
        fileMove: "",
        //languages:[{path:" "}],
        configuratorLibrary: false,
        //for tree file at click next
        identification: 0,
        actionsPath: {
          library: {
            edit: 'cdn/actions/library/edit',
            add: 'cdn/actions/library/add'
          },
          version: {
            edit: 'cdn/actions/version/edit',
            add: 'cdn/actions/version/add'
          }
        },
        table: '',
        listNoUpdate: [],
        theme_prepend: false,
        check_prepend: true
      }
    },
    computed: {
      //  management(){
      //    return this.closest("bbn-container").getComponent()
      //  },
      // for source colum of the table depandancies
      list() {
        if (bbn.fn.count(this.dataVersion.dependencies) > 0) {
          let d = this.dataVersion.dependencies.slice();
          return this.listLib.filter(lib => {
            let exists = false;
            for (let obj of d) {
              if (obj.lib_name === lib.value) {
                exists = true;
              }
            }
            return !exists;
          });
        }
        else {
          return this.listLib;
        }
      },
      //disabled or no input name version
      disabledEditVersion() {
        return (this.management.action.editVers || this.management.action.addVers) ? true : false
      },
      //for tree order files
      treeOrderSource() {
        if (this.referenceNodeTree.length) {
          let arr = [];
          for (let i in this.referenceNodeTree) {
            arr.push(this.referenceNodeTree[i]);
          }
          return arr
        }
        this.fileMove = "";
        return [];
      },
      //for first insert lib or no
      abilitationCheckedLatest() {
        return !!this.data.latest
      },
      //for form senction 2 first of save library
      complementaryData() {
        return {
          vname: this.dataVersion.version,
          files: this.treeOrderSource,
          languages: this.data.languages,
          themes: this.data.themes,
          new_name: this.newName,
          dependencies: this.dataVersion.dependencies,
          no_update_dependents: this.listNoUpdate,
          last_dependencies: this.dataVersion.slave_dependencies,
          name: this.source.name || "",
          is_latest: this.data.latest,
          theme_prepend: this.theme_prepend && (this.data.themes.length > 0) ? true : false
        }
      }
    },
    methods: {
      //for button form
      getCurrentButton() {
        if (this.management.action) {
          // case for add library of the toolbar first form
          if (this.management.action.addLib && !this.configuratorLibrary) {
            return [
              'cancel',
              {
                label: "Next",
                title: "next",
                class: "bbn-primary",
                icon: 'nf nf-fa-arrow_circle_right',
                //   disabled: (!this.source.row.title && !this.source.row.name)  ? true : false,
                action: () => { this.next() }
              }
            ];
          }
          // case for add library and click next for configurator library before saving
          if (this.management.action.addLib && this.configuratorLibrary) {
            return [
              {
                label: "Prev",
                title: "Prev",
                icon: 'nf nf-fa-arrow_circle_left',
                action: () => { this.configuratorLibrary = false }
              },
              'cancel',
              {
                label: "Save",
                icon: 'nf nf-fa-check_circle',
                class: "bbn-primary",
                action: () => {
                  if (this.referenceNodeTree.length) {
                    this.$refs.form_library.submit()
                  }
                  else {
                    alert(bbn._("No file selected"));
                  }
                }
              }
            ]
          }// case for edit table cdn managment or edit and add versions
          if (this.management.action.addVers || this.management.action.editVers || this.management.action.editLib) {
            return [
              'cancel',
              {
                label: "Save",
                class: "bbn-primary",
                icon: 'nf nf-fa-check_circle',
                action: () => {
                  //case edit library
                  if (this.management.action.editLib) {
                    if (this.source.row.title.length || this.source.row.name.length) {
                      this.$refs.form_library.submit()
                    }
                    else {
                      this.closest('bbn-popup').alert(bbn._("The name or the folder name for this library is missing"));
                    }
                  }//case edit or sdd version
                  else {
                    if (this.referenceNodeTree.length) {
                      this.$refs.form_library.submit()
                    }
                    else {
                      this.closest('bbn-popup').alert(bbn._("No file selected"));
                    }
                  }
                },
              }
            ]
          }
        }
        return ['cancel']
      },
      getInfo() {
        this.post(this.management.source.root + "github/info", {
          url: this.source.row.git,
          only_info: true
        },
          (d) => {
            if (d.data) {
              bbn.fn.each(d.data, (val, prop) => {
                // if ( (prop !== 'name') &&
                //   (prop !== 'latest') &&
                if (this.source.row[prop] !== undefined) {
                  this.source.row[prop] = val;
                  if (prop === 'licence') {
                    let lic = bbn.fn.getField(data.licences, 'licence', 'name', d.data[prop]);
                    if (!lic) {
                      lic = bbn.fn.getField(data.licences, 'licence', 'licence', d.data[prop]);
                    }
                    if (lic) {
                      this.source.row.licence = lic;
                    }
                  }
                }
              });
            }
          }
        );
      },
      success() {
        this.management.refreshManagement();
        this.$nextTick(() => {
          this.closest("bbn-popup").close();
          appui.success(bbn._('Success!'));
        });
      },
      failure() {
        appui.error(bbn._('A problem has occurred'));
      },//// only case of add library for info and select the files
      next() {
        //let's check if you do it next for the first time or not
        if (this.management.action.addLib &&
          this.source.row.name &&
          this.source.row.title
        ) {
          this.post(this.management.source.root + 'data/version/add', {
            folder: this.source.row.name,
            git_id_ver: this.source.row.git_id,
            git_user: this.source.row.user !== undefined ? this.source.row.user : false,
            git_repo: this.source.row.repo !== undefined ? this.source.row.repo : false,
            git_latest_ver: this.source.row.latest !== undefined ? this.source.row.latest : false
          }, d => {
            if (d.data && d.data.folders_versions) {
              this.$set(this, 'configuratorLibrary', true);
              this.currentButton = this.getCurrentButton();
              bbn.fn.each(d.data.folders_versions, folder => {

                for (let i in this.dataVersion) {
                  this.dataVersion[i] = (folder[i]) ? folder[i] : []
                  if (i === "themes_tree") {
                    this.dataVersion[i] = folder.files_tree ? folder.files_tree : [];
                  }
                }
                //for dropdown list library in table depanadancies
                for (let val of folder.lib_ver) {
                  if (bbn.fn.search(this.listLib, 'text', val.lib_title) < 0) {
                    this.listLib.push({ text: val.lib_title, value: val.lib_name });
                  }
                };
              });
            }
          }
          );
        }
        else {
          //if ( !$.isEmptyObject(this.dataVersion) ){
          if (Object.entries(this.dataVersion).length > 0) {
            this.configuratorLibrary = true;
          }
        }
      },
      /* FIRST INSERT LIBRARY */
      //for map tree in section "next"
      treeFiles(ele) {
        if (ele.items) {
          ele.items.forEach((item, idx) => {
            ele.items[idx] = this.treeFiles(item);
          });
        }

        let obj = {
          data: ele,
          fpath: ele.fpath,
          items: ele.items || [],
          icon: 'nf nf-fa-file',
          file: true,
          text: ele.text,
          //num: ele.items ? ele.items.length : 0,
          numChildren: ele.items ? ele.items.length : 0
        };
        if (obj.items.length > 0) {
          obj.file = false;
          obj.icon = 'nf nf-fa-folder';
        }
        return obj;
        /*return {
          data: ele,
          path: ele.path,
          items: ele.items || [],
          icon: ele.items ? 'nf nf-fa-folder' : 'nf nf-fa-file',
          text: ele.text,
          num: ele.items ? ele.items.length : 0,
          numChildren: ele.items ? ele.items.length : 0
        }*/
      },
      checkFile() {
        this.copyItemsTree = bbn.fn.extend(this.$refs.filesListTree.source, true);
        this.referenceNodeTree = this.$refs.filesListTree.checked;
      },
      uncheckFile() {
        this.referenceNodeTree = this.$refs.filesListTree.checked;
      },
      // return button delete in the table
      buttonDeleteLanguages() {
        return [{
          text: "destroy",
          icon: "nf nf-fa-trash",
          action: (row, col, id) => {
            return this.$refs.tableLanguages.delete(id, bbn._("Are you sure you want to continue?"));
          },
          notext: true
        }]
      },
      buttonDeleteThemes() {
        return [{
          label: "destroy",
          icon: "nf nf-fa-trash",
          action: (row, col, id) => {
            return this.$refs.tableThemes.delete(id, bbn._("Are you sure you want to continue?"));
            // if ( this.$refs.tableThemes.currentData.length === 0 ){
            //   this.check_prepend = false;
            // }
          },
          notext: true
        }]
      },
      // for order list file
      selectFileMove(file) {
        this.fileMove = file;
      },
      //FOR MOVE
      moveDown() {
        let sourceOrder = this.treeOrderSource,
          idx;
        this.treeOrderSource.forEach((file, i) => {
          if (this.fileMove === file) {
            idx = i;
          }
        });
        if (this.treeOrderSource[idx + 1] !== undefined) {
          let support = this.treeOrderSource[idx + 1];
          sourceOrder[idx + 1] = sourceOrder[idx];
          sourceOrder[idx] = support;
          this.referenceNodeTree = sourceOrder;
        }
        else {
          let support = sourceOrder.pop();
          sourceOrder.unshift(support);
          this.referenceNodeTree = sourceOrder;
        }
      },
      moveUp() {
        let sourceOrder = this.treeOrderSource,
          idx;
        this.treeOrderSource.forEach((file, i) => {
          if (this.fileMove === file) {
            idx = i;
          }
        });
        if (this.treeOrderSource[idx - 1] !== undefined) {
          let support = this.treeOrderSource[idx - 1];
          sourceOrder[idx - 1] = sourceOrder[idx];
          sourceOrder[idx] = support;
          this.referenceNodeTree = sourceOrder;
        }
        else {
          let support = sourceOrder.shift();
          sourceOrder.push(support);
          this.referenceNodeTree = sourceOrder;
          // let max = bbn.fn.count(this.treeOrderSource)-1,
          //     support = this.treeOrderSource[max];
          //
          // sourceOrder[max] = sourceOrder[idx];
          // sourceOrder[idx] = support;
          // this.referenceNodeTree = sourceOrder;
        }
      },
      //DEPANDANCIES TABLE
      //buttons table  depandacies
      buttonsTableDepandencies(row, col, idx) {
        return [
          {
            label: 'Delete',
            action: (row, col, id) => {
              return this.$refs.tableDependecies.delete(id, bbn._("Are you sure you want to continue?"));
            },
            icon: 'nf nf-fa-trash',
            title: 'delete',
            notext: true
          }
        ];
      },
      //at select a version depandacies render in this function for show at number version and not your id
      showVersion(ele) {
        return bbn.fn.getField(this.dataVersion.lib_ver, "version", "id_ver", ele.id_ver);
      },
      //at click editline table dependencies
      saveDependencies(row, col, idx) {
        //error in case no lib or version for dependencies
        if (!row.id_ver || !row.lib_name) {
          appui.error(bbn._("The version's ID is missing"));
        }
        else if (!row.lib_name) {
          appui.error(bbn._("The library's name is missing"));
        }
        //error in case no lib or version or existing lib  in list of dependencies
        else if ((this.dataVersion.dependencies.length > 0) &&
          (bbn.fn.search(this.dataVersion.dependencies, 'lib_name', row.lib_name) >= 0)
        ) {
          appui.error(bbn._("Dependencies already inserted"));
        }
        //if we insert a value that is not an integer as the order number
        else if (!Number.isInteger(row.order)) {
          appui.error(bbn._("The order should be a number"));
        }
        else {
          let obj = bbn.fn.extend({}, row)
          this.dataVersion.dependencies.push(obj);
          this.source.row.dependencies.push(obj);
          this.$nextTick(() => {
            this.$refs.tableDependecies.updateData();
          })
          //   this.$refs.tableDependecies._removeTmp();
        }
      },
      // in order to display dependency names in the table because the source that is attributed to the column is a computed and updates every time we insert a new dependency
      renderLibName(row) {
        let idx = bbn.fn.search(this.listLib, 'value', row.lib_name);
        if (idx > -1) {
          return this.listLib[idx]['value'];
        }
      },
      //for edit version
      checkedNode() {
        if (this.management.action.editVers) {
          this.$refs.filesListTree.checked = this.referenceNodeTree;
        }
      },
      //is called to the mounted in case of edit version
      editVersion() {
        this.configuratorLibrary = true;
        this.post(appui.plugins['appui-cdn'] + "/data/version/edit", { version: this.source.row.id, library: this.source.row.library }, d => {
          if (d.data !== undefined) {
            this.dataVersion.dependencies = d.data.dependencies;
            this.dataVersion.files_tree = d.data.files_tree;
            this.dataVersion.version = this.source.row.name;
            let arr = [];
            for (let obj of d.data.files) {
              arr.push(obj.path);
            }
            this.dataVersion.languages_tree = d.data.languages_tree;
            this.dataVersion.themes_tree = d.data.themes_tree;
            this.referenceNodeTree = arr;
            this.data.themes = d.data.themes;
            this.data.languages = d.data.languages;
            this.dataVersion.lib_ver = d.data.lib_ver;
            this.data.versions = d.data.versions;
            this.data.internal = this.source.row.internal;
            this.data.latest = this.source.row.is_latest;
            this.theme_prepend = d.data.theme_prepend;
            delete this.source.row.is_latest;
            delete this.source.row.files_tree;
            // for dropdown list dependencies
            for (let val of d.data.lib_ver) {
              if (bbn.fn.search(this.listLib, 'text', val.lib_title) < 0) {
                this.listLib.push({ text: val.lib_title, value: val.lib_name });
              }
            }
          }
        });
      },
      addVersion() {
        this.configuratorLibrary = true;
        this.dataVersion = this.source.row;
        this.data.versions = this.source.versions;
        this.dataVersion.themes_tree = this.source.row.files_tree;
        // for dropdown list dependencies
        for (let val of this.source.row.lib_ver) {
          if (bbn.fn.search(this.listLib, 'text', val.lib_title) < 0) {
            this.listLib.push({ text: val.lib_title, value: val.lib_name });
          }
        }
      },
      showTable(type) {
        this.table = (type === this.table) ? '' : type;
      },
      buttonUploadDepandance(ele) {
        let i = bbn.fn.search(this.listNoUpdate, 'name', ele.name)
        if (i > -1) {
          this.listNoUpdate.splice(i, 1);
        }
      },
      buttonNoUpload(ele) {
        let i = bbn.fn.search(this.listUpdate, 'name', ele.name)
        if (i === -1) {
          this.listNoUpdate.push(ele);
        }
      },
      /*return [
        {
          label: bbn._('Update last version'),
          action: ( row, col, id)=>{
            let i = bbn.fn.search(this.listUpdate, 'name', row.name)
            if ( i > -1 ){
              this.listUpdate.splice(i,1);
            }
            this.listUpdate.push(row);
          },
          icon: 'zmdi zmdi-thumb-up',
          title: bbn._('Update last version'),
          notext: true,

          style:"width:50%; color: green"
        },{
        label: bbn._('no update'),
        action: ( row, col, id)=>{
          this.listNoUpdate.push(row);
        },
        icon: 'nf nf-fa-ban',
        title: bbn._('No update'),
        notext: true,
        style:"width:50%; color: red"
      }]*/
      //},
      getDependent() {
        this.showTable('dependent');
      }
    },
    created() {
      editLib = this;
      this.currentButton = this.getCurrentButton();
      this.showForm = true;
    },
    mounted() {
      let licences = this.management.source.licences;
      if (licences.length) {
        for (let ele of licences) {
          this.licencesList.push({
            text: ele.name,
            value: ele.licence
          })
        }
      }
      //if edit version
      if (this.management.action.editVers) {
        this.editVersion();
      }//if add version
      if (this.management.action.addVers) {
        this.addVersion();
      }
      if (this.management.action.editLib) {
        this.newName = this.source.row.name;
      }
      if (this.management.action.addLib) {
        /*        let popup = this.closest('bbn-container').popup(),
                    id_popup = bbn.fn.count(popup.popups)-2;
                popup.close(id_popup);*/
      }
    },
    watch: {
      currentButton(val) {
        this.$set(this, 'showForm', false)
        this.$nextTick(() => {
          this.$set(this, 'showForm', true)
        })
      }
    },
    components: {
      'update': {
        template: `<bbn-switch v-model="update"
                              :value="true"
                              :novalue="false"
                              :noIcon="false"
                              offIcon="nf nf-fa-ban"
                              onIcon="nf nf-fa-check_circle"
                  ></bbn-switch>`,
        props: ['source'],
        data() {
          return {
            update: true
          }
        },
        watch: {
          update(val) {
            if (val === false) {
              editLib.buttonNoUpload(this.source);
            }
            else {
              editLib.buttonUploadDepandance(this.source);
            }
          }
        }
      },
      //button in title column grid add file language
      'languages': {
        template: `<bbn-button @click="openTreeLanguage"
                              :title="titleButton"
                              icon="nf nf-fa-plus"
                  ></bbn-button>`,
        props: ['source'],
        data() {
          return {
            titleButton: bbn._('Add language file'),
            sourceTree: editLib.dataVersion.languages_tree,
          }
        },
        methods: {
          openTreeLanguage() {
            this.getPopup({
              height: '70%',
              width: '30%',
              label: bbn._("Files"),
              component: 'appui-cdn-management-popup-tree_files',
              source: { tree: this.sourceTree, table: this.closest("bbn-form").$parent.data.languages },
              onClose: () => {
                this.closest('bbn-table').updateData()
              }
            });
          },
        }
      },
      //button in title column grid add themes language
      'themes': {
        template: `<bbn-button @click="openTreeThemes" :title="titleButton" icon="nf nf-fa-plus"></bbn-button>`,
        props: ['source'],
        data() {
          return {
            titleButton: bbn._('Add theme'),
            sourceTree: editLib.dataVersion.themes_tree,
          }
        },
        methods: {
          openTreeThemes() {
            this.getPopup({
              height: '70%',
              width: '30%',
              label: bbn._("Files"),
              component: 'appui-cdn-management-popup-tree_files',
              source: {
                tree: this.sourceTree,
                table: this.closest("bbn-form").$parent.data.themes
              },
              onClose: () => {
                this.closest('bbn-table').updateData()
              }
            });
          },
        }
      },
      'prepend_theme': {
        template: `<bbn-checkbox v-model="checkTheme"
                                :value="true"
                                :novalue="false"
                                :label="labelCheck"
                                v-if="show"
                  ></bbn-checkbox>`,
        data() {
          return {
            show: editLib.table === "themes",
            labelCheck: bbn._('Prepend the theme files'),
            checkTheme: editLib.complementaryData.theme_prepend
          }
        },
        watch: {
          checkTheme(val) {
            editLib.$set(editLib, 'theme_prepend', val)
          }
        }
      },
      'versions': {
        template: `<bbn-dropdown :source="listVersion" @change="getVersion"></bbn-dropdown>`,
        props: ['source'],
        data() {
          return {
            lib_ver: editLib.dataVersion.lib_ver
          }
        },
        methods: {
          getVersion(version) {
            this.source.id_ver = version;
          }
        },
        computed: {
          listVersion() {
            if (this.source.lib_name) {
              let arr = [];
              for (let val of this.lib_ver) {
                if (val.lib_name === this.source.lib_name) {
                  arr.push({ text: val.version, value: val.id_ver });
                }
              }
              return arr
            }
            return []
          }
        }
      }
    }
  }
})();
