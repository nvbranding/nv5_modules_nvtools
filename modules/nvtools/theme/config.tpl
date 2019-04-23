<!-- BEGIN: main -->
<?xml version='1.0'?>
<theme>
	<info>
		<name>{THEME_INFO.info_name}</name>
		<author>{THEME_INFO.info_author}</author>
		<website>{THEME_INFO.info_website}</website>
		<description>{THEME_INFO.info_description}</description>
		<thumbnail>{THEME_INFO.theme}.jpg</thumbnail>
	</info>
	<layoutdefault>{THEME_INFO.layoutdefault}</layoutdefault>
	<positions>
		<!-- BEGIN: position -->
		<position>
			<tag>[{DATA_POSITION.tag}]</tag>
			<name>{DATA_POSITION.name}</name>
			<name_vi>{DATA_POSITION.name_vi}</name_vi>
		</position>
		<!-- END: position -->
	</positions>
	<setlayout>
		<layout>
			<name>body</name>
			<funcs>statistics:main,allreferers,allcountries,allbrowsers,allos,allbots,referer</funcs>
		</layout>
	</setlayout>	
</theme>
<!-- END: main -->