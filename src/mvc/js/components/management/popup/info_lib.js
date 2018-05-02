(() => {
  return {
    data(){
      return {
        infos:[
          {text: 'Title', value: this.source.info.title ? this.source.info.title : '--' },
          {text: 'Folder name', value: this.source.info.name ? this.source.info.name : '--' },
          {text: 'Function name', value: this.source.info.fname ? this.source.info.fname : '--' },
          {text: 'Latest version', value: this.source.info.latest ? this.source.info.latest : '--' },
          {text: 'Author', value: this.source.info.author ? this.source.info.author : '--' },
          {text: 'Description', value: this.source.info.description ? this.source.info.description : '--' },
          {text: 'Licence', value: this.source.info.licence ? this.source.info.licence : '--' },
          {text: 'WebSite', value: this.source.info.website ? this.source.info.website : '--' },
          {text: 'Download', value: this.source.info.download_link ? this.source.info.download_link : '--' },
          {text: 'Documentation', value: this.source.info.doc_link ? this.source.info.doc_link : '--' },
          {text: 'Support', value: this.source.info.git ? this.source.info.git : '--' },
          {text: 'Github', value: this.source.info.support_link ? this.source.info.support_link : '--' },
        ],
        versions: this.source.versions
      }
    },
    computed: {
      colsInfo(){
        if ( this.source.info ){
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
      },
    }
  }
})();