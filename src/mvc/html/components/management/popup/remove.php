<div class="bbn-padded">
  <bbn-form :action="source.action"
            :data="infoForDelete"
            @success="success"
            @failure="failure"
            class="bbn-full-screen"
            :prefilled="true"
  >
    <div class="bbn-100 bbn-padded">
      <span class="bbn-b bbn-xxl" v-text="deleteElement"></span>
      <div class="bbn-vmiddle">
        <bbn-switch v-model="infoForDelete.removeFolder"
                    :value="true"
                    :novalue="false"
        ></bbn-switch>
        <span class="bbn-b bbn-padded" v-text="deleteFolderElement"></span>
      </div>
    </div>
  </bbn-form>
</div>
