<!-- HTML Document -->

<div class="bbn-overlay">
  <bbn-form action="<?=APPUI_CDN_ROOT?>publishing/custom"
            :source="formData"
            :scrollable="true">
    <div class="bbn-overlay bbn-flex-height">
      <div class="bbn-w-100">
        <bbn-dropdown :source="source.languages" v-model="formData.language"></bbn-dropdown>
      </div>
      <div class="bbn-flex-fill">
        <bbn-multiselect :source="source.components"
                         class="bbn-h-100"
                         v-model="formData.components"></bbn-multiselect>
      </div>
    </div>
  </bbn-form>
</div>