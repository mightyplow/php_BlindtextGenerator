<?php

/* BlindtextGenerator
 *
 * Can be used to create blindtexts. It uses a set of latin words to create either a given amount
 * of words or sentences.
 *
 * @author mightyplow
 */
class BlindtextGenerator {
   const
      UNIT_SENTENCE = 'sentence',
      UNIT_WORD = 'word',

      DEFAULT_MIN_WORDS_PER_SENTENCE = 3,
      DEFAULT_MAX_WORDS_PER_SENTENCE = 9;

   private static
      $punctuationMarks = ['.', '!', '?'],

      $unitFunctions = [
         self::UNIT_SENTENCE => 'createSentence',
         self::UNIT_WORD => 'getRandomWord'
      ],

      $availableWords = [
         'post', 'emensos', 'insuperabilis', 'expeditionis', 'eventus', 'languentibus',
         'partium', 'animis', 'quas', 'periculorum', 'varietas', 'fregerat',
         'et', 'laborum', 'nondum', 'tubarum', 'cessante', 'clangore',
         'vel', 'milite', 'locato', 'per', 'stationes', 'hibernas',
         'fortunae', 'saevientis', 'procellae', 'tempestates', 'alias', 'rebus',
         'infudere', 'communibus', 'multa', 'illa', 'dira', 'facinora',
         'caesaris', 'galli', 'qui', 'ex', 'squalore', 'imo',
         'miseriarum', 'in', 'aetatis', 'adultae', 'primitiis', 'ad',
         'principale', 'culmen', 'insperato', 'saltu', 'provectus', 'ultra',
         'terminos', 'potestatis', 'delatae', 'procurrens', 'asperitate', 'nimia',
         'cuncta', 'foedabat', 'propinquitate', 'enim', 'regiae', 'stirpis',
         'gentilitateque', 'etiam', 'tum', 'constantini', 'nominis', 'efferebatur',
         'fastus', 'si', 'plus', 'valuisset', 'ausurus', 'hostilia',
         'auctorem', 'suae', 'felicitatis', 'ut', 'videbatur', 'cuius',
         'acerbitati', 'uxor', 'grave', 'accesserat', 'incentivum', 'germanitate',
         'augusti', 'turgida', 'supra', 'modum', 'quam', 'hannibaliano',
         'regi', 'fratris', 'filio', 'antehac', 'constantinus', 'iunxerat',
         'pater', 'megaera', 'quaedam', 'mortalis', 'inflammatrix', 'adsidua',
         'humani', 'cruoris', 'avida', 'nihil', 'mitius', 'maritus',
         'paulatim', 'eruditiores', 'facti', 'processu', 'temporis', 'nocendum',
         'clandestinos', 'versutosque', 'rumigerulos', 'conpertis', 'leviter', 'addere',
         'male', 'suetos', 'falsa', 'placentia', 'sibi', 'discentes',
         'adfectati', 'regni', 'artium', 'nefandarum', 'calumnias', 'insontibus',
         'adfligebant', 'eminuit', 'autem', 'inter', 'humilia', 'supergressa',
         'iam', 'impotentia', 'fines', 'mediocrium', 'delictorum', 'nefanda',
         'clematii', 'cuiusdam', 'alexandrini', 'nobilis', 'mors', 'repentina',
         'socrus', 'cum', 'misceri', 'generum', 'flagrans', 'eius',
         'amore', 'non', 'impetraret', 'ferebatur', 'palatii', 'pseudothyrum',
         'introducta', 'oblato', 'pretioso', 'reginae', 'monili', 'id',
         'adsecuta', 'est', 'honoratum', 'comitem', 'orientis', 'formula',
         'missa', 'letali', 'omnino', 'scelere', 'nullo', 'contactus',
         'idem', 'clematius', 'nec', 'hiscere', 'loqui', 'permissus',
         'occideretur', 'post', 'hoc', 'impie', 'perpetratum', 'quod',
         'aliis', 'quoque', 'timebatur', 'tamquam', 'licentia', 'crudelitati',
         'indulta', 'suspicionum', 'nebulas', 'aestimati', 'quidam', 'noxii',
         'damnabantur', 'quorum', 'pars', 'necati', 'alii', 'puniti',
         'bonorum', 'multatione', 'actique', 'laribus', 'suis', 'extorres',
         'relicto', 'praeter', 'querelas', 'lacrimas', 'stipe', 'conlaticia',
         'victitabant', 'civili', 'iustoque', 'imperio', 'voluntatem', 'converso',
         'cruentam', 'claudebantur', 'opulentae', 'domus', 'clarae', 'vox',
         'accusatoris', 'ulla', 'licet', 'subditicii', 'his', 'malorum',
         'quaerebatur', 'acervis', 'saltem', 'specie', 'tenus', 'crimina',
         'praescriptis', 'legum', 'committerentur', 'aliquotiens', 'fecere', 'principes',
         'saevi', 'sed', 'quicquid', 'caesaris', 'implacabilitati', 'sedisset',
         'velut', 'fas', 'iusque', 'perpensum', 'confestim', 'urgebatur',
         'impleri', 'excogitatum', 'super', 'homines', 'ignoti', 'vilitate',
         'ipsa', 'parum', 'cavendi', 'colligendos', 'rumores', 'antiochiae',
         'latera', 'destinarentur', 'relaturi', 'quae', 'audirent', 'hi',
         'peragranter', 'dissimulanter', 'honoratorum', 'circulis', 'adsistendo', 'pervadendoque',
         'divites', 'egentium', 'habitu', 'noscere', 'poterant', 'audire',
         'latenter', 'intromissi', 'posticas', 'regiam', 'nuntiabant', 'observantes',
         'conspiratione', 'concordi', 'fingerent', 'cognita', 'duplicarent', 'peius',
         'laudes', 'vero', 'supprimerent', 'caesaris', 'invitis', 'conpluribus',
         'formido', 'inpendentium', 'exprimebat', 'interdum', 'acciderat', 'siquid',
         'penetrali', 'secreto', 'citerioris', 'vitae', 'ministro', 'praesente',
         'paterfamilias', 'uxori', 'susurrasset', 'aurem', 'amphiarao', 'referente',
         'aut', 'marcio', 'quondam', 'vatibus', 'inclitis', 'postridie',
         'disceret', 'imperator', 'ideoque', 'parietes', 'arcanorum', 'soli',
         'conscii', 'timebantur', 'adolescebat', 'obstinatum', 'propositum', 'erga',
         'haec', 'similia', 'scrutanda', 'stimulos', 'admovente', 'regina',
         'abrupte', 'mariti', 'fortunas', 'trudebat', 'exitium', 'praeceps',
         'eum', 'potius', 'lenitate', 'feminea', 'veritatis', 'humanitatisque',
         'viam', 'reducere', 'utilia', 'suadendo', 'deberet', 'gordianorum',
         'actibus', 'factitasse', 'maximini', 'truculenti', 'illius', 'imperatoris',
         'rettulimus', 'coniugem', 'novo', 'denique', 'perniciosoque', 'exemplo',
         'gallus', 'ausus', 'inire', 'flagitium', 'romae', 'ultimo',
         'dedecore', 'temptasse', 'aliquando', 'dicitur', 'gallienus', 'adhibitis',
         'paucis', 'clam', 'ferro', 'succinctis', 'vesperi', 'tabernas',
         'palabatur', 'conpita', 'quaeritando', 'graeco', 'sermone', 'erat',
         'inpendio', 'gnarus', 'quid', 'de', 'caesare', 'quisque',
         'sentiret', 'confidenter', 'agebat', 'urbe', 'ubi', 'pernoctantium',
         'luminum', 'claritudo', 'dierum', 'solet', 'imitari', 'fulgorem',
         'postremo', 'agnitus', 'saepe', 'iamque', 'prodisset', 'conspicuum',
         'se', 'fore', 'contemplans', 'nisi', 'luce', 'palam',
         'egrediens', 'agenda', 'putabat', 'seria', 'cernebatur', 'quidem',
         'medullitus', 'multis', 'gementibus', 'agebantur'
      ];

   private
      $minWordsPerSentence = self::DEFAULT_MIN_WORDS_PER_SENTENCE,
      $maxWordsPerSentence = self::DEFAULT_MAX_WORDS_PER_SENTENCE;


   public function createText ($num, $unit = self::UNIT_WORD) {
      if (!key_exists($unit, self::$unitFunctions)) {
         throw new Exception('Invalid unit parameter');
      }

      $fnUnit = self::$unitFunctions[$unit];
      $arTexts = [];

      for ($i = 0; $i < $num; $i++) {
         $arTexts[] = self::$fnUnit();
      }

      return implode(' ', $arTexts);
   }

   public function createSentence () {
      $numWords = rand($this->minWordsPerSentence, $this->maxWordsPerSentence);
      $sentenceWords = [];

      for ($i = 0; $i < $numWords; $i++) {
         do {
            $sRandWord = self::getRandomWord();
         } while (in_array($sRandWord, $sentenceWords));

         // capitalize first letter when first word of sentence
         // also capitalize when a random number between 0 and 3 is 0 -> just to get more capital letters
         if (($i !== 0 && rand(0, 3))) {
            $sentenceWords[] = $sRandWord;
         } else {
            $sentenceWords[] = ucwords($sRandWord);
         }
      }

      return implode(' ', $sentenceWords) . self::getRandomPunctuationMark();
   }

   public static function getRandomWord () {
      return self::$availableWords[(rand(0, count(self::$availableWords) - 1))];
   }

   private static function getRandomPunctuationMark () {
      return self::$punctuationMarks[rand(0, count(self::$punctuationMarks) - 1)];
   }

   public function setMinWordsPerSentence ($minWordsPerSentence) {
      $this->minWordsPerSentence = $minWordsPerSentence;
   }

   public function setMaxWordsPerSentence ($maxWordsPerSentence) {
      $this->maxWordsPerSentence = $maxWordsPerSentence;
   }
}
