<!-- HTML Document -->

<div class="bbn-overlay">
  <bbn-form action="<?= APPUI_CDN_ROOT ?>publishing/custom"
            :source="formData"
            :scrollable="true">
    <div class="bbn-overlay bbn-flex-height">
      <div class="bbn-w-100 bbn-spadded bbn-header">
        <span><?= _('Language') ?>:</span>
        <bbn-dropdown :source="source.languages"
                      v-model="formData.language"/>
        <span class="bbn-left-space">DOMContentLoaded <?= _('event') ?></span>
        <bbn-checkbox :value="true"
                      :novalue="false"
                      v-model="formData.domcontentloaded"/>
      </div>
      <div class="bbn-flex-fill">
        <bbn-multiselect :source="source.components"
                         class="bbn-h-100"
                         v-model="formData.components"/>
      </div>
    </div>
  </bbn-form>
</div>