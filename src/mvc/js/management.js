(()=>{
  let management;
  return {
    mixins: [bbn.vue.localStorageComponent],
    data(){
      return{
        searchContent: [],
        search: false,
        action:{
          post: "",
          addLib: false,
          addVers: false,
          editLib: false,
          editVers: false
        }
      }
    },
    computed:{
      sourceTable(){
        if ( this.searchContent.length ){
          //filter data table for search libraries
          return this.searchContent
        }//all libraries for table
        return this.source.all_lib
      }
    },
    methods:{
      buttons(row, col, idx){
        return [
          {
            text: "Info",
            command:()=>{
              this.info(row, col, idx);
            },
            icon: 'fa fa-info',
            title: 'info',
            notext: true
          },
          {
            text: 'Edit',
            command:()=>{
              this.actions('editLib');
              this.editLibrary(row, col, idx);
            },
            icon: 'fa fa-edit',
            title: 'edit',
            notext: true
          },
          {
           text: 'Delete',
           command:()=>{
             this.removeLib(row,col,idx);
           },
           icon: 'fa fa-trash',
           title: 'delete',
           notext: true
          }
        ];
      },

      /* this function is activated at the click of the info button and
       *  its task is to display a popup with all the information related to the selected library
       */
      info(row, col, idx){
        let obj = {
          info : row,
          versions: []
        };
        bbn.fn.post(this.source.root + 'data/versions', {id_lib: row.name}, d =>{
          if ( d.data.success ){
            if ( d.data.versions ){
              for ( let ele of d.data.versions ){
                obj.versions.push({
                  name: ele.name,
                  date: moment(ele.date_added).format('DD/MM/YYYY')
                });
              }
            }
            this.getPopup().open({
              width: 580,
              height: 700,
              title: bbn._("Info") + ': '+ row.name,
              component: 'appui-cdn-management-popup-info_lib',
              source: obj
            })
          }
        });
      },
      editLibrary(row, col, idx){
        return this.$refs.cdn_management.edit(row,  {
          title: this.source.lng.editLib,
          height: '95%',
          width: '85%'
        }, idx);
      },
      /*edit(row, col, idx){
        return this.$refs.table.edit(row, bbn._("Edit Library"), idx);
      },*/
      actions(type){
        switch(type){
          case 'addVers':
            this.action.post = this.source.root + 'actions/version/add';
            break;
          case 'editVers':
            this.action.post = this.source.root + 'actions/version/edit';
          break;
          case 'addLib':
            this.action.post = this.source.root + 'actions/library/add';
            break;
          case 'editLib':
            this.action.post = this.source.root + "actions/library/edit";
            break;
        }
        for(let typeAction in this.action){
          if ( typeAction === type ){
            this.action[typeAction] = true;
          }
          else{
            if (typeAction !== 'post'){
              this.action[typeAction] = false;
            }
          }
        }
      },
      removeLib(row, col, idx){
        /*appui.confirm(bbn._("Delete library") + " "+  row.name + "?" , ()=>{
          bbn.fn.post(this.source.root + 'actions/library/delete', {name: row.name}, (d) => {
            if ( d.data.success ){
              appui.success(bbn._("Delete"));
              this.refreshManagement();
            }
            else{
              appui.error(bbn._("Error"));
            }
          });
        });*/
       this.getPopup().open({
          width: 500,
          height: 200,
          title: bbn._("Remove library"),
          component: 'appui-cdn-management-popup-remove',
          source: $.extend(row, {deleteLib: true, action:this.source.root + 'actions/library/delete'})
        })

      },
      //for render icon table
      showIconAuthor(ele){
        return ( ele.author && ele.author.length ) ? '<div class="bbn-c"><i class="fa fa-user bbn-xl" title="' + ele.author + '"></i></div>' : '';
      },
      showIconLicense(ele){
        return ( ele.licence && ele.licence.length ) ? "<div class='bbn-c'><i class='fa fa-copyright'></i></div>" : '';
      },
      showIconWeb(ele){
        return ( ele.website && ele.website.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele.website + "'" + "target='_blank'>" + "<i class='fa fa-globe'</i></a></div>" : '';
      },
      showIconDownload(ele){
        return ( ele.download_link && ele.download_link.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele.download_link + "'" + "target='_blank'>" + "<i class='fa fa-download'</i></a></div>" : '';
      },
      showIconDoc(ele){
        return ( ele.doc_link && ele.doc_link.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele.doc_link + "'" + "target='_blank'>" + "<i class='fa fa-book'</i></a></div>" : '';
      },
      showIconGit(ele){
        return ( ele.git && ele.git.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele.git + "'" + "target='_blank'>" + "<i class='fa fa-github'</i></a></div>" : '';
      },
      showIconSupportLink(ele){
        return ( ele.support_link && ele.support_link.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele.support_link + "'" + "target='_blank'>" + "<i class='fa fa-ambulance'</i></a></div>" : '';
      },
      refreshManagement(){
        bbn.fn.post(this.source.root + "management", {refresh: true}, d => {
          if( d.all_lib != undefined ){
            this.source.all_lib = d.all_lib;
            this.$nextTick(()=>{
              this.$refs.cdn_management.updateData()
            });
          }
        });
      }
    },
    created(){
      management = this;
      let mixins = [{
        data(){
          return {
            management: management
          }
        },
      }];
    },
    watch:{//for search libray in table
        sourceTable(val){
          if ( val && this.search ){
            this.$nextTick(()=>{
              this.$refs.cdn_management.updateData()
            });
          }
        }
    },
    /*components:{
      'deleteLib':{
        template: `<div class="bbn-padded"><span class="bbn-b bbn-xxl" v-text="textDelete"></span><div class="bbn-vmiddle"><span class="bbn-b bbn-r" v-text="deleteFolderText"></span><bbn-switch class="bbn-l"></bbn-switch></div></div>`,
        props: ['source'],
        data(){
          return{
            textDelete: bbn._("Delete library") +  " " +  this.source.title + "?",
            deleteFolderText: bbn._("Do you want to delete the folder" ) + " " +   this.source.name + " " +  bbn._("in cdn?")
          }
        }
      }
    }*/
  }
})();
