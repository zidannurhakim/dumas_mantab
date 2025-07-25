(function($) {
    $.fn.orgChart = function(options) {
        var opts = $.extend({}, $.fn.orgChart.defaults, options);
        return new OrgChart($(this), opts);        
    };

    $.fn.orgChart.defaults = {
        data: [{id:1, name:'Root', parent: 0}],
        showControls: false,
        allowEdit: false,
        onAddNode: null,
        onDeleteNode: null,
        onClickNode: null,
        newNodeText: 'Add Child'
    };

    function OrgChart($container, opts){
        var data = opts.data;
        var nodes = {};
        var rootNodes = [];
        this.opts = opts;
        this.$container = $container;
        var self = this;

        this.draw = function(){
            $container.empty();
            
            if (rootNodes.length === 0) {
                console.error("Error: Root node tidak ditemukan. Pastikan data memiliki root node dengan parent: 0");
                return;
            }

            // Tambahkan pengecekan sebelum render
            if (typeof rootNodes[0] !== 'undefined' && typeof rootNodes[0].render === 'function') {
                $container.append(rootNodes[0].render(opts));
            } else {
                console.error("Error: Root node tidak memiliki fungsi render.");
                return;
            }

            $container.find('.node').click(function(){
                if(self.opts.onClickNode !== null){
                    self.opts.onClickNode(nodes[$(this).attr('node-id')]);
                }
            });

            if(opts.allowEdit){
                $container.find('.node h2').click(function(e){
                    var thisId = $(this).parent().attr('node-id');
                    self.startEdit(thisId);
                    e.stopPropagation();
                });
            }

            $container.find('.org-add-button').click(function(e){
                var thisId = $(this).parent().attr('node-id');
                if(self.opts.onAddNode !== null){
                    self.opts.onAddNode(nodes[thisId]);
                } else {
                    self.newNode(thisId);
                }
                e.stopPropagation();
            });

            $container.find('.org-del-button').click(function(e){
                var thisId = $(this).parent().attr('node-id');
                if(self.opts.onDeleteNode !== null){
                    self.opts.onDeleteNode(nodes[thisId]);
                } else {
                    self.deleteNode(thisId);
                }
                e.stopPropagation();
            });
        };

        this.startEdit = function(id){
            var inputElement = $('<input class="org-input" type="text" value="'+nodes[id].data.name+'"/>');
            $container.find('div[node-id='+id+'] h2').replaceWith(inputElement);
            var commitChange = function(){
                var h2Element = $('<h2>'+nodes[id].data.name+'</h2>');
                if(opts.allowEdit){
                    h2Element.click(function(){
                        self.startEdit(id);
                    });
                }
                inputElement.replaceWith(h2Element);
            };  
            inputElement.focus();
            inputElement.keyup(function(event){
                if(event.which == 13){
                    commitChange();
                } else {
                    nodes[id].data.name = inputElement.val();
                }
            });
            inputElement.blur(function(){
                commitChange();
            });
        };

        this.newNode = function(parentId){
            var nextId = Object.keys(nodes).length;
            while(nextId in nodes){
                nextId++;
            }

            self.addNode({id: nextId, name: '', parent: parentId});
        };

        this.addNode = function(data){
            var newNode = new Node(data);
            nodes[data.id] = newNode;
            nodes[data.parent].addChild(newNode);

            self.draw();
            self.startEdit(data.id);
        };

        this.deleteNode = function(id){
            for(var i=0;i<nodes[id].children.length;i++){
                self.deleteNode(nodes[id].children[i].data.id);
            }
            nodes[nodes[id].data.parent].removeChild(id);
            delete nodes[id];
            self.draw();
        };

        this.getData = function(){
            return Object.values(nodes).map(node => node.data);
        };

        // Constructor: inisialisasi node
        for(var i in data){
            var node = new Node(data[i]);
            nodes[data[i].id] = node;
        }

        // Buat struktur parent-child
        for(var i in nodes){
            if(nodes[i].data.parent == 0){
                rootNodes.push(nodes[i]);
            } else if (nodes[nodes[i].data.parent]) { // Pastikan parent ada sebelum addChild
                nodes[nodes[i].data.parent].addChild(nodes[i]);
            } else {
                console.warn(`Warning: Parent dengan ID ${nodes[i].data.parent} tidak ditemukan untuk node ${nodes[i].data.id}`);
            }
        }

        // Gambar org chart jika ada root node
        $container.addClass('orgChart');
        self.draw();
    }

    function Node(data){
        this.data = data;
        this.children = [];
        var self = this;

        this.addChild = function(childNode){
            this.children.push(childNode);
        };

        this.removeChild = function(id){
            this.children = this.children.filter(child => child.data.id !== id);
        };

        this.render = function(opts){
            var childLength = self.children.length;
            var mainTable = "<table cellpadding='0' cellspacing='0' border='0'>";
            var nodeColspan = childLength > 0 ? 2 * childLength : 2;
            mainTable += `<tr><td colspan='${nodeColspan}'>${self.formatNode(opts)}</td></tr>`;

            if(childLength > 0){
                mainTable += "<tr class='lines'><td colspan='"+(childLength*2)+"'><table cellpadding='0' cellspacing='0' border='0'><tr class='lines x'><td class='line left half'></td><td class='line right half'></td></tr></table></td></tr>";
                
                var linesCols = self.children.map((_, i) => 
                    `<td class='line ${i === 0 ? "left" : "left top"}'></td><td class='line ${i === childLength - 1 ? "right" : "right top"}'></td>`
                ).join('');
                
                mainTable += `<tr class='lines v'>${linesCols}</tr>`;
                mainTable += `<tr>${self.children.map(child => `<td colspan='2'>${child.render(opts)}</td>`).join('')}</tr>`;
            }

            mainTable += '</table>';
            return mainTable;
        };

        this.formatNode = function(opts){
            var nameString = self.data.name ? `<h2>${self.data.name}</h2>` : '';
            var descString = self.data.description ? `<p>${self.data.description}</p>` : '';
            var buttonsHtml = opts.showControls ? `<div class='org-add-button'>${opts.newNodeText}</div><div class='org-del-button'></div>` : '';

            return `<div class='node' node-id='${self.data.id}'>${nameString}${descString}${buttonsHtml}</div>`;
        };
    }
})(jQuery);
