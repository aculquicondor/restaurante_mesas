module.exports = function(grunt) {

  grunt.initConfig({
    concat: {
      options: {
        separator: '\n'
      },
      dist: {
        src: [
            'web/app/js/controllers.js',
            'web/app/js/controllers/menu_items/*.js',
            'web/app/js/controllers/orders/*.js',
            'web/app/js/controllers/stores/*.js',
            'web/app/js/controllers/tables/*.js',
            'web/app/js/services.js',
            'web/app/js/services/menu_items/*.js',
            'web/app/js/services/orders/*.js',
            'web/app/js/services/reservations/*.js',
            'web/app/js/services/stores/*.js',
            'web/app/js/services/tables/*.js',
            'web/app/js/main.js'
        ],
        dest: 'web/app/js/all.js'
      }
    },
      jshint: {
      files: ['Gruntfile.js', 'web/app/js/*.js'],
      options: {
        globals: {
          jQuery: true
        }
      }
    },
    watch: {
      files: ['<%= jshint.files %>'],
      tasks: ['jshint']
    }
  });

  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');

  grunt.registerTask('default', ['concat']);

};
