
Vue.component('appui-cdn-management-popup-library_edit', {
  name: 'appui-cdn-management-popup-library_edit',
  template: '#bbn-tpl-component-appui-cdn-management-popup-library_edit',
  props: ['source'],
  data(){
    //return  this.source.row
    return {
      title: this.source.row.title,
      name: this.source.row.name,
      fname: this.source.row.fname,
      description: this.source.row.description,
      author: this.source.row.author,
      website: this.source.row.website,
      download_link: this.source.row.download_link,
      doc_link: this.source.row.doc_link,
      git: this.source.row.git,
      support_link: this.source.row.support_link,
      licence:""
    }
  },
  computed: {
    licencesList(){
      if ( this.source.licences ){
        let arr = [];
        for(let ele of this.source.licences ){
          arr.push({
            text: ele.name,
            value: ele.licence
          })
        }

        return arr;
      }
      return [];
    }
  }
});
