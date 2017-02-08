var _header_spec, _signal_spec, _signals = [];

_header_spec = [
  {
    "name": "version",
    "length": 8
  }, {
    "name": "patient_id",
    "length": 80
  }, {
    "name": "recording_id",
    "length": 80
  }, {
    "name": "start_date",
    "length": 8
  }, {
    "name": "start_time",
    "length": 8
  }, {
    "name": "number_of_bytes",
    "length": 8
  }, {
    "name": "reserved",
    "length": 44
  }, {
    "name": "num_data_records",
    "length": 8
  }, {
    "name": "duration_of_data_record",
    "length": 8
  }, {
    "name": "num_signals_in_data_record",
    "length": 4
  }
];

_signal_spec = [
  {
    "name": "label",
    "length": 16
  }, {
    "name": "transducer_type",
    "length": 80
  }, {
    "name": "physical_dimensions",
    "length": 8
  }, {
    "name": "physical_min",
    "length": 8
  }, {
    "name": "physical_max",
    "length": 8
  }, {
    "name": "digital_min",
    "length": 8
  }, {
    "name": "digital_max",
    "length": 8
  }, {
    "name": "prefiltering",
    "length": 80
  }, {
    "name": "num_samples_in_data_record",
    "length": 8
  }
];

function EDFFile(edf_path) {

  var _specs, i, j, l, len, ref, spec;
  this.edf_path = edf_path;

  _signals = [];
  
  this._handle = this.edf_path;
  this._header_item = {};
  this._signal_item = {};
  for (i = j = 0, ref = parseInt(this.get_header_item("num_signals_in_data_record")); 0 <= ref ? j <= ref : j >= ref; i = 0 <= ref ? ++j : --j) {
    _specs = {};
    for (l = 0, len = _signal_spec.length; l < len; ++l) {
      spec = _signal_spec[l];
      _specs[spec.name] = this.get_signal_item(i, spec.name);
    }
    _signals.push(_specs);
  }
}

/**
* Retourne le nom de l'objet
*/
EDFFile.prototype.get_name = function() {
  return 'EDFFile';
}

/**
* Retourne la légende
*/
EDFFile.prototype.get_legend = function() {
  return this.legend;
}

/**
* Retourne la série
*/
EDFFile.prototype.get_series = function(i) {
  if(typeof i === 'number'){
    return this.series[i];
  }else{
    return this.series;
  }
}

/**
* Retourne le navigateur
*/
EDFFile.prototype.get_navigator = function() {
  return this.serieNavigator;
}

/**
* Retourne la limite de points à afficher
*/
EDFFile.prototype.get_limit_points_display = function() {
  return 40000;
}

EDFFile.prototype.get_header_offset = function() {
  return 256 + (this.get_header_item("num_signals_in_data_record") * 256);
};

EDFFile.prototype.get_file_duration = function() {
  return this.get_header_item("duration_of_data_record") * this.get_header_item("num_data_records");
};

EDFFile.prototype._get_header_spec = function(name) {
  var _o, j, len, position, x;
  position = 0;
  for (j = 0, len = _header_spec.length; j < len; ++j) {
    x = _header_spec[j];
    if (x.name === name) {
      _o = x;
      _o["position"] = position;
      return _o;
    } else {
      position += x.length;
    }
  }
};

EDFFile.prototype._get_signal_spec = function(signal_index, name) {
  var _o, j, len, position, x;
  position = 256;
  for (j = 0, len = _signal_spec.length; j < len; ++j) {
    x = _signal_spec[j];
    if (x.name === name) {
      _o = x;
      _o["position"] = position + (x.length * signal_index);
      return _o;
    } else {
      position += this.get_header_item("num_signals_in_data_record") * x.length;
    }
  }
};

EDFFile.prototype._get_signal_obj = function(signal_index) {
  var _o;
  _o = _signals[signal_index];
  _o["gain"] = (parseFloat(_o.physical_max) - parseFloat(_o.physical_min)) / (parseFloat(_o.digital_max) - parseFloat(_o.digital_min));
  _o["offset"] = (_o.physical_max / _o.gain) - _o.digital_max;
  _o["sample_rate"] = _o.num_samples_in_data_record / this.get_header_item("duration_of_data_record");
  return _o;
};

EDFFile.prototype._get_buffer_slice = function(length, position) {
  
  var k = this._handle.slice(position, position + length);

  return k;
};

EDFFile.prototype.get_header_item = function(name) {
  var spec;
  if (this._header_item[name] != null) {
    return this._header_item[name];
  }
  spec = this._get_header_spec(name);

  var dataView = new Int8Array(this._get_buffer_slice(spec.length, spec.position)),
  texte = [];

  for(var i=0, c=dataView.length; i<c; ++i){
    texte[i] = String.fromCharCode(dataView[i]);
  }
  
  this._header_item[name] = texte.join('').trim();
  return this._header_item[name];
};

EDFFile.prototype.get_signal_item = function(signal_index, name) {
  var _i, spec;
  _i = name + "_" + signal_index;
  if (this._signal_item[_i] != null) {
    return this._signal_item[_i];
  }
  spec = this._get_signal_spec(signal_index, name);

  var dataView = new Int8Array(this._get_buffer_slice(spec.length, spec.position)),
  texte = [];

  for(var i=0, c=dataView.length; i<c; ++i){
    texte[i] = String.fromCharCode(dataView[i]);
  }

  this._signal_item[_i] = texte.join('').trim();
  return this._signal_item[_i];
};

EDFFile.prototype.get_signal_data = function(signal_index, start, end) {
  var _r, _samples, _signal, _signal_index, base_offset, block_size, block_time, blocks_to_read, channel_block, channel_seek, channel_size, exact_time, i, j, l, len, len1, m, n, normal, p, raw, records_to_skip, ref, ref1, total_seconds;
  if (Array.isArray(signal_index)) {
    _r = [];
    for (j = 0, len = signal_index.length; j < len; ++j) {
      _signal_index = signal_index[j];
      _r.push(this.get_signal_data(_signal_index, start, end));
    }
    return _r;
  }
  if (_signals[signal_index] == null) {
    throw new Error("Invalid Signal index specified.");
  }
  block_size = 0;
  for (l = 0, len1 = _signals.length; l < len1; ++l) {
    _signal = _signals[l];
    block_size += _signal.num_samples_in_data_record * this.get_header_item("duration_of_data_record") * 2;
  }

  total_seconds = end - start;
  blocks_to_read = Math.ceil(total_seconds / this.get_header_item("duration_of_data_record"));
  channel_seek = 0;
  for (_signal_index = m = 0, ref = signal_index; 0 <= ref ? m < ref : m > ref; _signal_index = 0 <= ref ? ++m : --m) {
    channel_seek += this.get_signal_item(_signal_index, "num_samples_in_data_record") * 2;
  }
  channel_size = this.get_signal_item(_signal_index, "num_samples_in_data_record") * 2;
  records_to_skip = start * this.get_header_item("duration_of_data_record");
  base_offset = (records_to_skip * block_size) + this.get_header_offset();
  _signal = this._get_signal_obj(signal_index);
  _samples = [];

  for (i = n = 0, ref1 = blocks_to_read; 0 <= ref1 ? n < ref1 : n > ref1; i = 0 <= ref1 ? ++n : --n) {
    
    channel_block = this._get_buffer_slice(channel_size, base_offset + (i * block_size) + channel_seek);
    block_time = (records_to_skip + i) * this.get_header_item("duration_of_data_record");
    p = 0;

    while (p < channel_block.byteLength) {

      raw = new Int16Array(channel_block.slice(p, p+2));

      normal = (raw[0] + _signal.offset) * _signal.gain;
      exact_time = block_time + (p / 2) / _signal.sample_rate;
      _samples.push({
        "time": exact_time,
        "data": normal
      });
      p += 2;
    }
  }
  return _samples;
};


/**
* Méthode pour récupérer l'ensemble des signaux entre deux points de temps
*/
EDFFile.prototype.get_all_signals_data = function(start, end) {
  
  var numSignal = this.get_header_item('num_signals_in_data_record'),
      sampleRate = Math.round(this.get_signal_item(0, 'num_samples_in_data_record')/this.get_header_item('duration_of_data_record')),
      dataEDF = [],
      channel_block = [],
      channel_seek = [],
      channel_size = [],
      _signal = [],
      channel_block_length = [],
      block_size = 0,
      total_seconds = end - start,
      blocks_to_read = Math.ceil(total_seconds / this.get_header_item("duration_of_data_record")),
      records_to_skip = start * this.get_header_item("duration_of_data_record"),
      i = j = 0,
      value = [];


  var _signal_index, max, base_offset, m, p, raw, ref;
  
 
  for (var l = 0, len1 = _signals.length; l < len1; ++l) {
    _signal = _signals[l];
    block_size += _signal.num_samples_in_data_record * this.get_header_item("duration_of_data_record") * 2;
  }

  
  base_offset = (records_to_skip * block_size) + this.get_header_offset();
  
 
  for(var k=0 ; k<numSignal ; ++k){
    channel_seek[k] = 0;
    signal_index = k;
    dataEDF[k] = [];

    for (var _signal_index = m = 0, ref = signal_index; 0 <= ref ? m < ref : m > ref; _signal_index = 0 <= ref ? ++m : --m) {
      channel_seek[k] += this.get_signal_item(_signal_index, "num_samples_in_data_record") * 2;
    }

    channel_size[k] = this.get_signal_item(_signal_index, "num_samples_in_data_record") * 2;
    _signal[k] = this._get_signal_obj(signal_index);
  }

  while(i <= blocks_to_read) {
    for(var k=0 ; k<numSignal ; ++k){
      channel_block[k] = this._get_buffer_slice(channel_size[k], base_offset + (i * block_size) + channel_seek[k]);
      channel_block_length[k] = parseInt(channel_block[k].byteLength);
    }
    

    max = Math.max.apply(null, channel_block_length);

    p = 0;
    while (p <= max) {
      value = [];

      for(var k=0 ; k<numSignal ; ++k){
       
          if(p < channel_block_length[k]-2){
            raw = new Int16Array(channel_block[k].slice(p, p+2));  

            value[k] = (raw[0] + _signal[k].offset) * _signal[k].gain;
          }else{
            value[k] = 0;
          }

      }
      
      for(var k=0; k<numSignal; ++k){
        dataEDF[k][j] = [(start + j/sampleRate)*1000*sampleRate, value[k]];
      }
      
      p += 2;
      ++j;
    }
    ++i;
  }

  return dataEDF;

};

/**
* Méthode pour récupérer la légende, le navigateur (Highchart) et les signaux
*/
EDFFile.prototype.get_view_signals_data = function(num_points_display) {
  
  var numSignal = this.get_header_item('num_signals_in_data_record'),
      sampleRate = Math.round(this.get_signal_item(0, 'num_samples_in_data_record')/this.get_header_item('duration_of_data_record')),
      dataEDF = [],
      serieNavigator = [],
      legend = [],
      channel_block = [],
      channel_seek = [],
      channel_size = [],
      _signal = [],
      channel_block_length = [],
      start = 0,
      end = this.get_header_item('num_data_records'),
      tmin = 0, // On place la navigation à la moitié du signal
      tmax = tmin + Math.round(num_points_display/sampleRate), // On veut num_points_display pts pour l'affichage
      value = [],
      block_size = 0,
      total_seconds = end - start,
      blocks_to_read = Math.ceil(total_seconds / this.get_header_item("duration_of_data_record")),
      records_to_skip = start * this.get_header_item("duration_of_data_record"),
      i = j = 0;

  var _signal_index, max, base_offset, m, p, raw, ref, val;

  if(tmax > end){
    tmax = end;
  }
 
  for (var l = 0, len1 = _signals.length; l < len1; ++l) {
    _signal = _signals[l];
    block_size += _signal.num_samples_in_data_record * this.get_header_item("duration_of_data_record") * 2;
  }

  base_offset = (records_to_skip * block_size) + this.get_header_offset();

  // Définition des attributs de chaque signaux
  for(var k=0 ; k<numSignal ; ++k){
    channel_seek[k] = 0;
    signal_index = k;
    dataEDF[k] = [];
    legend[k] = this.get_signal_item(k, 'label');

    for (var _signal_index = m = 0, ref = signal_index; 0 <= ref ? m < ref : m > ref; _signal_index = 0 <= ref ? ++m : --m) {
      channel_seek[k] += this.get_signal_item(_signal_index, "num_samples_in_data_record") * 2;
    }

    channel_size[k] = this.get_signal_item(_signal_index, "num_samples_in_data_record") * 2;
    _signal[k] = this._get_signal_obj(signal_index);
  }

  // On parcours le fichier EDF
  while (i < blocks_to_read) {

    for(var k=0 ; k<numSignal ; ++k){
      channel_block[k] = this._get_buffer_slice(channel_size[k], base_offset + (i * block_size) + channel_seek[k]);
      channel_block_length[k] = parseInt(channel_block[k].byteLength);
    }
    
    p = 0;
    if(i < tmin  || i > tmax){
      
      raw = new Int16Array(channel_block[0].slice(p, p+2));
      val = (raw[0] + _signal[0].offset) * _signal[0].gain;

      serieNavigator[i] = [i*1000*sampleRate, parseFloat(val)];

    }else{
      
      max = Math.max.apply(null, channel_block_length);

      while (p <= max) {
        value = [];

        for(var k=0 ; k<numSignal ; ++k){
         
            if(p < channel_block_length[k] - 2){
              raw = new Int16Array(channel_block[k].slice(p, p+2));  

              value[k] = (raw[0] + _signal[k].offset) * _signal[k].gain;
            }else{
              value[k] = 0;
            }

        }
        
        for(var k=0; k<numSignal; ++k){
          dataEDF[k][j] = [(tmin + j/sampleRate)*1000*sampleRate, value[k]];
        }
        if(p == 0){
          serieNavigator[i] = [i*1000*sampleRate, parseFloat(value[0])];
        }
        
        p += 2;
        ++j;
        
      }
    }
    ++i;
  }

  this.legend = legend;
  this.series = dataEDF;
  this.serieNavigator = serieNavigator;
};