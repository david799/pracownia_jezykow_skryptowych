import tkinter as tk
from tkinter.filedialog import askopenfilename

class IndesignReplaceApp(tk.Frame):
    INDD_FILE_PATH = None
    EXCEL_FILE_PATH = None
    WORD_FILE_PATH = None
    SKIP_WHITE_SPACES = True
    USE_WORD = False
    def createWidgets(self):
        self.enter_indd = tk.Entry(self)
        self.indd_entry_text = tk.StringVar()
        self.enter_indd["textvariable"] = self.indd_entry_text
        self.enter_indd.grid(row=0)

        self.enter_word_excel = tk.Entry(self)
        self.word_excel_entry_text = tk.StringVar()
        self.enter_word_excel["textvariable"] = self.word_excel_entry_text
        self.enter_word_excel.grid(row=1)

        self.choose_indd = tk.Button(self)
        self.choose_indd.grid(row=0, column=1)
        self.choose_indd["text"] = "Choose indd"
        self.choose_indd["command"] = self.choose_indd_file

        self.choose_word_excel = tk.Button(self)
        self.choose_word_excel.grid(row=1, column=1)
        self.choose_word_excel["text"] = "Choose word/excel"
        self.choose_word_excel["command"] = self.choose_word_excel_file

        self.word_excel_label = tk.Label(self)
        self.word_excel_label.grid(row=2)
        self.word_excel_label["text"] = "Word/Excel"

        self.word_excel_toggle = tk.Button(self)
        self.word_excel_toggle.grid(row=2, column=1)
        self.word_excel_toggle["text"] = "Word"
        self.word_excel_toggle["command"] = self.word_excel_change_toggle

        self.white_spaces_label = tk.Label(self)
        self.white_spaces_label.grid(row=3)
        self.white_spaces_label["text"] = "White spaces"

        self.white_spaces_toggle = tk.Button(self)
        self.white_spaces_toggle.grid(row=3, column=1)
        self.white_spaces_toggle["text"] = "Yes"
        self.white_spaces_toggle["command"] = self.white_spaces_change_toggle


        # just to make space
        tk.Label(self).grid(row=4)

        self.warning_label = tk.Label(self)
        self.warning_label.grid(row=5)
        self.warning_label["text"] = "Not replaced: 5/150"

        self.start_replacing = tk.Button(self)
        self.start_replacing.grid(row=5, column=1)
        self.start_replacing["text"] = "Start"
        self.start_replacing["command"] = self.start_replacing_process

    def __init__(self, master=None):
        tk.Frame.__init__(self, master)
        self.pack()
        self.createWidgets()

    def choose_indd_file(self):
        IndesignReplaceApp.INDD_FILE_PATH = askopenfilename()
        self.indd_entry_text.set(IndesignReplaceApp.INDD_FILE_PATH)


    def choose_word_excel_file(self):
        if IndesignReplaceApp.USE_WORD:
            IndesignReplaceApp.WORD_FILE_PATH = askopenfilename()
            self.word_excel_entry_text.set(IndesignReplaceApp.WORD_FILE_PATH)
        else:
            IndesignReplaceApp.EXCEL_FILE_PATH = askopenfilename()
            self.word_excel_entry_text.set(IndesignReplaceApp.EXCEL_FILE_PATH)

    def white_spaces_change_toggle(self):
        IndesignReplaceApp.SKIP_WHITE_SPACES = not IndesignReplaceApp.SKIP_WHITE_SPACES
        self.white_spaces_toggle["text"] = "Yes" if IndesignReplaceApp.SKIP_WHITE_SPACES else "No"

    def word_excel_change_toggle(self):
        IndesignReplaceApp.USE_WORD = not IndesignReplaceApp.USE_WORD
        self.word_excel_toggle["text"] = "Excel" if IndesignReplaceApp.USE_WORD else "Word"

    def start_replacing_process(self):
        IndesignReplaceApp.INDD_FILE_PATH = self.indd_entry_text.get()
        if IndesignReplaceApp.USE_WORD:
            IndesignReplaceApp.WORD_FILE_PATH = self.word_excel_entry_text.get()
        else:
            IndesignReplaceApp.EXCEL_FILE_PATH = self.word_excel_entry_text.get()
        print("start")

root = tk.Tk()
app = IndesignReplaceApp(master=root)
app.mainloop()
root.destroy()