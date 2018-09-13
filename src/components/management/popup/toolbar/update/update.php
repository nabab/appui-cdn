<bbn-table :source="source">
  <bbns-column title= "<?=_('Library')?>"
              field= "title"
  ></bbns-column>
  <bbns-column title= "<?=_('Local version')?>"
              field= "local"
              cls= "bbn-c"
  ></bbns-column>
  <bbns-column title= "<?=_('Latest version')?>"
              field= "latest"
              cls= "bbn-c w3-red"
  ></bbns-column>
  <bbns-column title= " "
              width= 110
              field= "title"
              :buttons= "buttons"
  ></bbns-column>
</bbn-table>
