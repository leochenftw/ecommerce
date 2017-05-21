<?php
use SaltedHerring\Debugger;

class ProductImporter extends BuildTask {
	protected $title = 'Product Importer';
	protected $description = 'Import product from CSV file';

	protected $enabled = true;

	public function run($request) {
		if ( $request->isGet() ){
            print '<h2>Existing: ' . Versioned::get_by_stage('ProductPage', 'Stage')->count() . '</h2>';
			print '<form enctype= "multipart/form-data" method="post">';
			print '  <input type="file" name="CsvFile" id="CsvFile" />';
			print '  <input type="submit" name="doUpload" id="doUpload" value="Upload" />';
			print '</form>';
		}elseif ( $request->isPost() ){
			/*$loader = new CsvBulkLoader('NZAirportsMember');
			$results = $loader->load($_FILES['CsvFile']['tmp_name']);*/
            // Debugger::inspect($_FILES['CsvFile']);
			if ($_FILES['CsvFile']['type'] != 'application/vnd.ms-excel') {
				print '<h2>Wrong file type. Must be csv file</h2>';
				print '<p><a href="/dev/tasks/ProductImporter">Try again</a></p>';
				die;
			}

			$file = fopen($_FILES['CsvFile']['tmp_name'], 'r+');
			$lines = array();
			while( ($row = fgetcsv($file)) !== FALSE ) {
				$lines[] = $row;
			}
			/*
			[0] => Email
			[1] => FirstName
			[2] => Surname
			[3] => Password
			*/

            // Debugger::inspect($lines[0]);

			if (!is_array($lines[0])) {
				print '<h2>Wrong CSV format</h2>';
				print '<p><a href="/dev/tasks/ProductImporter">Try again</a></p>';
				die;
			}


			$num_err = 0;

			if (!in_array('﻿Barcode', $lines[0])) {
				$num_err++;
				print '<p>Barcode column is missing</p>';
			}

			if (!in_array('Title', $lines[0])) {
				$num_err++;
				print '<p>Title column is missing</p>';
			}

            if (!in_array('Alias', $lines[0])) {
				$num_err++;
				print '<p>Alias column is missing</p>';
			}

			if (!in_array('StockCount', $lines[0])) {
				$num_err++;
				print '<p>StockCount column is missing</p>';
			}

			if (!in_array('Measurement', $lines[0])) {
				$num_err++;
				print '<p>Measurement column is missing</p>';
			}

            if (!in_array('Weight', $lines[0])) {
				$num_err++;
				print '<p>Weight column is missing</p>';
			}

            if (!in_array('Supplier', $lines[0])) {
				$num_err++;
				print '<p>Supplier column is missing</p>';
			}

			if ($num_err > 0) {
				print '<h2>Invalid CSV file. Please contact web administrator</h2>';
				print '<p><a href="/dev/tasks/ProductImporter">Try again</a></p>';
				die;
			}

			$n = 0;
			for ($i = 1; $i < count($lines); $i++){
				$barcode = $lines[$i][0];
                $title = $lines[$i][1];
                $alias = $lines[$i][2];
                $stockcount = $lines[$i][3];
                $measurement = $lines[$i][4];
                $weight = $lines[$i][5];
                $supplier = $lines[$i][6];

                $product = Versioned::get_by_stage('ProductPage', 'Stage')->filter(array('Barcode' => $barcode))->first();
                if (empty($product)) {
                    $product = new ProductPage();
                }

                $product->Barcode       =   $barcode;
                $product->Title         =   $title;
                $product->Alias         =   $alias;
                $product->StockCount    =   $stockcount;
                $product->Measurement   =   $measurement;
                $product->Weight        =   $weight;
                $product->Supplier      =   $supplier;

                $product->writeToStage('Stage');

                if ($product->isPublished()) {
                    $product->doPublish();
                }

                $n++;
			}
			print '<p>'.$n.' product(s) imported</p>';
			print '<p><a href="/dev/tasks/ProductImporter">Import another CSV file</a></p>';
		}
	}
}
