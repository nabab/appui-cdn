(()=>{
  return {
    data(){
      return{

      }
    },
    methods:{
      buttons(row, col, idx){
        return [
          {
            text: "Info",
            command:()=>{
              //'<a class="k-button k-grid-d-info fa fa-info" href="javascript:;"></a>'
              this.info(row, col, idx);
            },
            icon: 'fa fa-info',
            title: 'info',
            notext: true
          },
          {
            text: 'Edit',
            command:()=>{
              this.edit(row, col, idx);
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
      info(row, col, idx){
        bbn.vue.closest(this, 'bbn-tab').popup().open({
          width: 850,
          height: 450,
          title: bbn._("Info") + ': '+ row.name,
          component: this.$options.components['cdn-management-info-lib'],
          source: row
        });
      },
      edit(row, col, idx){
        return this.$refs.table.edit(row, bbn._("Edit Library"), idx);
      },
      removeLib(row, col, idx){
        return this.$refs.table.edit(row, bbn._("Delete library"), idx);
      },
      create(o){
        bbn.fn.post('cdn/configurations', o.data, function(d){
          if ( d.data && d.data.length ){
            o.success(d.data);
          }
          else {
            o.error();
          }
        });
      },
      update(o){
        bbn.fn.post('cdn/configurations', o.data, function(d){
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
          bbn.fn.post('cdn/configurations', {hash: o.data.hash}, function(d){
            if ( d.data.success ){
              o.success();
            }
            else{
              o.error();
            }
          });
        }
      },
      //for render
      showIconAuthor(ele){
        return ( ele.author && ele.author.length ) ? '<div class="bbn-c"><i class="fa fa-user bbn-xl" title="' + ele + '"></i></div>' : '';
      },
      showIconLicense(ele){
        return ( ele.licence && ele.licence.length ) ? "<div class='bbn-c'><i class='fa fa-copyright'></i></div>" : '';
      },
      showIconWeb(ele){
        return ( ele.website && ele.website.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele + "'" + "target='_blank'>" + "<i class='fa fa-globe'</i></a></div>" : '';
      },
      showIconDownload(ele){
        return ( ele.download_link && ele.download_link.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele + "'" + "target='_blank'>" + "<i class='fa fa-download'</i></a></div>" : '';
      },
      showIconDoc(ele){
        return ( ele.doc_link && ele.doc_link.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele + "'" + "target='_blank'>" + "<i class='fa fa-book'</i></a></div>" : '';
      },
      showIconGit(ele){
        return ( ele.git && ele.git.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele + "'" + "target='_blank'>" + "<i class='fa fa-github'</i></a></div>" : '';
      },
      showIconSupportLink(ele){
        return ( ele.support_link && ele.support_link.length ) ? "<div class='bbn-c'><a class='appui-no' href='"  + ele + "'" + "target='_blank'>" + "<i class='fa fa-ambulance'</i></a></div>" : '';
      },
    },
    components: {
      //for show info
      'cdn-management-info-lib':{
        template: '#cdn-management-info-lib',
        name:'cdn-management-info-lib',
        props: ['source'],
        data(){
          return {
            infos:[
              {text: 'Title', value: this.source.title ? this.source.title : '--' },
              {text: 'Folder name', value: this.source.name ? this.source.title : '--' },
              {text: 'Function name', value: this.source.fname ? this.source.title : '--' },
              {text: 'Latest version', value: this.source.latest ? this.source.title : '--' },
              {text: 'Author', value: this.source.author ? this.source.title : '--' },
              {text: 'Description', value: this.source.description ? this.source.description : '--' },
              {text: 'Licence', value: this.source.licence ? this.source.licence : '--' },
              {text: 'WebSite', value: this.source.website ? this.source.website : '--' },
              {text: 'Download', value: this.source.download_link ? this.source.download_link : '--' },
              {text: 'Documentation', value: this.source.doc_link ? this.source.doc_link : '--' },
              {text: 'Support', value: this.source.git ? this.source.git : '--' },
              {text: 'Github', value: this.source.support_link ? this.source.support_link : '--' },
            ]
          }
        },
        computed: {
          cols(){
            if ( this.source ){
              return [
                {
                  title: ' ',
                  field: 'text',
                  width: "180"
                },
                {
                  title: ' ',
                  field: 'value',
                  width: "300"
                }
              ];
            }
            return [];
          }
        }
      },
    }
  }
})();
