DELIMITER $$

DROP VIEW IF EXISTS `dblist`.`getrominfo`$$

CREATE ALGORITHM=UNDEFINED DEFINER=`benoit`@`%` SQL SECURITY DEFINER VIEW `getrominfo` AS select `benoit`.`romtype` AS `romtype`,

(
	case
		when romtype in ('mb8877a','microsol','moonsound','svi738fdc','svi328cart','tc8566af','wd2793','msx-audio','fmpac','msx-music','msx-dos2','fmsxpatched','kanji','jisyo','bunsetsu','panasonic16','panasonic32','svi328cart','SVI738FDC','svi80col','wd2783','national','fsa1fm2','fsa1fm1','cx5m','caspatch','0xc000') then 'System'
		when romtype in ('0x4000','auto','basic','keyboardmaster','synthesizer','playball','0x0000','0x8000','MatraInk') then 'rom'
		when romtype like 'scc%' 	then 'sccpluscart'
		when extra = 'system' 		then 'systemrom' 
		else 'megarom'
	end
) AS Mapper,

(
	case
		when (dump = 'goodmsx') 	then '<original value="true">GoodMSX</original>'
		when (dump = 'author') 		then '<original value="true">Author</original>' else '<original value="false"/>'
	end
) AS Dumper,

(
	case
		when (romtype = '0x4000') 	then '<start>0x4000</start>'
		when (romtype = 'basic') 	then '<start>0x8000</start>'
	end
) AS StartAddress,

(
	case
		when (romtype = 'scc') 				then '<boot>scc</boot>'
		when (romtype = 'scc+') 			then '<boot>scc+</boot>'
		when (romtype = 'Synthesizer') 		then '<type>Synthesizer</type>'
		when (romtype = 'MatraInk') 		then '<type>MatraInk</type>'
		when (romtype = 'Playball') 		then '<type>Playball</type>'
		when (romtype in ('0x0000','0x4000','0x8000','basic','0xC000')) then '<type>Normal</type>'
	end
) AS BootType,

(
	case
		when ((length(meta) > 1) and (length(remark) > 1)) 	then concat(meta,' / ',remark)
		when (length(meta) > 1) 							then concat(meta)
		when (length(remark) > 1) 							then concat(remark)
	end
) AS Remark,

	lcase(sha1) 	AS sha1,
	company 		AS company,
	country 		AS country,
	gamename 		AS gamename,
	extra 			AS system,
	year 			AS year 
from benoit
where (length(sha1) > 32)$$

DELIMITER ;