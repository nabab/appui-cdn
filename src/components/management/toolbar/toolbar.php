<!-- Libraries toolbar -->
<div class="toolbar">
  <div style="float:left; padding-left: 5px">
    <span style="margin-right: 5px"><?=_('Reload')?></span>
    <bbn-button @click="management.refreshManagement"
                icon="nf nf-fa-sync_alt"
                style="margin-right: 5px"
    >
      <?=_("Management")?>
    </bbn-button>
    <!-- Add library button -->
    <span style="margin: 0 5px 0 10px"><i class="nf nf-fa-archive"></i><?=_('Libraries')?></span>
    <bbn-button @click="add"
                icon="nf nf-fa-plus"
                style="margin-right: 5px"
    >
      <?=_("Add")?>
    </bbn-button>

    <!-- GitHub Updates-->
    <span style="margin: 0 5px 0 10px"><i class="nf nf-fa-github"></i>GitHub:</span>
    <bbn-button @click="checkUpdate"
                icon="nf nf-fa-sync_alt"
                style="margin-right: 5px"
                title="<?=_("Check updates from GitHub")?>"
    >
      <?=_("Check updates")?>
    </bbn-button>
    <!-- TODO incomplete-->
    <bbn-button @click="showUpdate"
                icon="nf nf-fa-cubes"
                style="margin-right: 5px"
                title="<?=_("Show updates")?>"
                :disabled= "disabledButton"
    >
      <span v-if="totalUpdateList >0" v-text="totalUpdateList"></span>
      <?=_('Updates')?>
    </bbn-button>
  </div>
  <!-- Search library -->
  <div style="float: right">
    <i class="nf nf-fa-search" style="margin: 0 5px"></i>
    <bbn-input style="width: 300px"
               placeholder="Search library"
               v-model="searchNameLibrary"
    ></bbn-input>
  </div>
</div>
