(()=>{
  let management;
  return {
    mixins: [bbn.vue.localStorageComponent],
    created(){
      management = this;
      let mixins = [{
        data(){
          return {
            management: management
          }
        },
      }];
      bbn.vue.setComponentRule('cdn/components/management', 'appui-cdn-management');
      //for button in colums action
      bbn.vue.addComponent('popup/info_lib', mixins);
      bbn.vue.addComponent('popup/toolbar/update', mixins);
      bbn.vue.addComponent('popup/tree_files', mixins);
      bbn.vue.addComponent('popup/toolbar/library_add', mixins);
      bbn.vue.addComponent('library_edit', mixins);
      bbn.vue.addComponent('libraries_toolbar', mixins);
      bbn.vue.unsetComponentRule();
    },
    data(){
      return{}
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
            bbn.vue.closest(this, 'bbn-tab').popup().open({
              width: 480,
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
          title: bbn._("Edit Library"),
          height: '95%',
          width: '85%'
        }, idx);
      },
      /*edit(row, col, idx){
        return this.$refs.table.edit(row, bbn._("Edit Library"), idx);
      },*/
      removeLib(row, col, idx){
        bbn.fn.log("delete", row, col, idx)
        bbn.fn.confirm(bbn._("Delete library") + " "+  row.name + "?" , ()=>{
          bbn.fn.post(this.source.root + 'actions/library/delete', {name: row.name}, (d) => {
            if ( d.data.success ){
              appui.success(bbn._("Delete"));
              this.$refs.cdn_management.updateData();
            }
            else{
              appui.error(bbn._("Error"));
            }
          });
        });
      },
      /*create(o){
        bbn.fn.post(this.source.root +'configurations', o.data, function(d){
          if ( d.data && d.data.length ){
            o.success(d.data);
          }
          else {
            o.error();
          }
        });
      },
      update(o){
        bbn.fn.post(this.source.root + 'configurations', o.data, function(d){
          if ( d.data ){
            o.success(d.data);
          }
          else {
            o.error();
          }
        });
      },
      destroy(o){
        if ( o.data.hash !== undefined ){
          bbn.fn.post(this.source.root +'configurations', {hash: o.data.hash}, function(d){
            if ( d.data.success ){
              o.success();
            }
            else{
              o.error();
            }
          });
        }
      },*/
      //for render
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
    }
  }
})();
